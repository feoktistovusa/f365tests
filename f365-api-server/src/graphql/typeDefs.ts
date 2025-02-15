import { gql } from "graphql-tag";

export const typeDefs = gql`
  scalar Date

  type Booking {
    id: ID!
    doctorName: String!
    date: Date!
    hour: Int!
    patientFullName: String!
    comments: String!
    deleted: Boolean!
  }

  type Query {
    bookingById(id: ID!): BookingResponse!
    bookingsByDate(date: Date!): BookingsResponse!
  }

  type Mutation {
    createBooking(
      doctorName: String!
      date: Date!
      hour: Int!
      patientFullName: String!
      comments: String!
    ): BookingResponse!

    updateBooking(
      id: ID!
      doctorName: String
      date: Date
      hour: Int
      patientFullName: String
      comments: String
    ): BookingResponse!

    deleteBooking(id: ID!): DeleteBookingResponse!
  }

  # Standard API response format
  type BookingResponse {
    success: Boolean!
    message: String!
    data: Booking
  }

  type BookingsResponse {
    success: Boolean!
    message: String!
    data: [Booking]
  }

  type DeleteBookingResponse {
    success: Boolean!
    message: String!
  }
`;
