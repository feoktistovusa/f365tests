import { AppDataSource } from "../config/data-source";
import { Booking } from "../entities/Booking";
import { GraphQLScalarType, Kind } from "graphql";
import axios from "axios";

const bookingRepository = AppDataSource.getRepository(Booking);

// Helper function for date validation
const isValidDate = (date: string) => /^\d{4}-\d{2}-\d{2}$/.test(date);

// Standardized error response
const errorResponse = (message: string) => ({
  success: false,
  message,
  data: null,
});

export const resolvers = {
  Date: new GraphQLScalarType({
    name: "Date",
    description: "Date scalar type",
    parseValue(value) {
      return typeof value === "string" && isValidDate(value)
        ? value
        : {
            success: false,
            message: "Invalid date format. Expected YYYY-MM-DD.",
          };
    },
    serialize(value) {
      return value instanceof Date
        ? value.toISOString().split("T")[0]
        : typeof value === "string" && isValidDate(value)
          ? value
          : { success: false, message: "Invalid date format." };
    },
    parseLiteral(ast) {
      return ast.kind === Kind.STRING && isValidDate(ast.value)
        ? ast.value
        : {
            success: false,
            message: "Invalid date format. Expected YYYY-MM-DD.",
          };
    },
  }),

  Query: {
    async bookingById(_: any, { id }: { id: number }) {
      const booking = await bookingRepository.findOne({
        where: { id, deleted: false },
      });
      return booking
        ? { success: true, message: "Booking found.", data: booking }
        : errorResponse("Booking not found.");
    },

    async bookingsByDate(_: any, { date }: { date: string }) {
      if (!isValidDate(date))
        return errorResponse("Invalid date format. Expected YYYY-MM-DD.");

      const bookings = await bookingRepository.find({
        where: { date, deleted: false },
      });

      return bookings.length > 0
        ? {
            success: true,
            message: `Found ${bookings.length} bookings.`,
            data: bookings,
          }
        : errorResponse("No bookings found for the given date.");
    },

    async patientById(_: any, { id }: { id: number }) {
      const MAIN_SERVER = process.env.MAIN_SERVER || 'http://nginx';

      try {
        const response = await axios.get(`${MAIN_SERVER}/api/patients/${id}`);
        const patient = response.data;

        return {
          success: true,
          message: "Patient found.",
          data: {
            id: patient.id,
            title: patient.title,
            firstName: patient.firstName,
            lastName: patient.lastName,
            dob: patient.dob,
            createdAt: patient.createdAt,
            updatedAt: patient.updatedAt,
          },
        };
      } catch (error) {
        console.log(error)
        if (axios.isAxiosError(error)) {
          return error.response && error.response.status === 404
            ? errorResponse("Patient not found.")
            : errorResponse("Failed to fetch patient data.");
        }
        return errorResponse("An unexpected error occurred.");
      }
    }
  },

  Mutation: {
    async createBooking(_: any, args: any) {
      const { doctorName, date, hour, patientFullName, comments } = args;

      if (typeof doctorName !== "string" || doctorName.length > 250)
        return errorResponse(
          "Invalid doctorName. Must be a string with max 250 characters.",
        );

      if (!isValidDate(date))
        return errorResponse("Invalid date format. Expected YYYY-MM-DD.");

      if (typeof hour !== "number" || hour < 0 || hour > 23)
        return errorResponse(
          "Invalid hour. Must be an integer between 0 and 23.",
        );

      if (typeof patientFullName !== "string" || patientFullName.length > 250)
        return errorResponse(
          "Invalid patientFullName. Must be a string with max 250 characters.",
        );

      if (typeof comments !== "string" || comments.length > 500)
        return errorResponse(
          "Invalid comments. Must be a string with max 500 characters.",
        );

      const booking = bookingRepository.create({
        doctorName,
        date,
        hour,
        patientFullName,
        comments,
      });
      const savedBooking = await bookingRepository.save(booking);

      return {
        success: true,
        message: "Booking created successfully.",
        data: savedBooking,
      };
    },

    async updateBooking(
      _: any,
      { id, doctorName, date, hour, patientFullName, comments }: any,
    ) {
      const booking = await bookingRepository.findOne({
        where: { id, deleted: false },
      });

      if (!booking) return errorResponse("Booking not found.");

      // Validate and update only provided fields
      if (doctorName !== undefined) {
        if (typeof doctorName !== "string" || doctorName.length > 250) {
          return errorResponse(
            "Invalid doctorName. Must be a string with max 250 characters.",
          );
        }
        booking.doctorName = doctorName;
      }

      if (date !== undefined) {
        if (!isValidDate(date)) {
          return errorResponse("Invalid date format. Expected YYYY-MM-DD.");
        }
        booking.date = date;
      }

      if (hour !== undefined) {
        if (typeof hour !== "number" || hour < 0 || hour > 23) {
          return errorResponse(
            "Invalid hour. Must be an integer between 0 and 23.",
          );
        }
        booking.hour = hour;
      }

      if (patientFullName !== undefined) {
        if (
          typeof patientFullName !== "string" ||
          patientFullName.length > 250
        ) {
          return errorResponse(
            "Invalid patientFullName. Must be a string with max 250 characters.",
          );
        }
        booking.patientFullName = patientFullName;
      }

      if (comments !== undefined) {
        if (typeof comments !== "string" || comments.length > 500) {
          return errorResponse(
            "Invalid comments. Must be a string with max 500 characters.",
          );
        }
        booking.comments = comments;
      }

      const updatedBooking = await bookingRepository.save(booking);

      return {
        success: true,
        message: "Booking updated successfully.",
        data: updatedBooking,
      };
    },
    async deleteBooking(_: any, { id }: { id: number }) {
      const booking = await bookingRepository.findOne({
        where: { id, deleted: false },
      });

      if (!booking) return { success: false, message: "Booking not found." };

      booking.deleted = true;
      await bookingRepository.save(booking);

      return {
        success: true,
        message: `Booking with ID ${id} has been soft deleted.`,
      };
    },
  },
};
