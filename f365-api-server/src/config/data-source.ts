import "reflect-metadata";
import { DataSource } from "typeorm";
import { Booking } from "../entities/Booking";

export const AppDataSource = new DataSource({
  type: "mysql",
  host: "db",
  port: 3306,
  username: "app",
  password: "ChangeMe",
  database: "api",
  entities: [Booking],
  migrations: ["src/migrations/*.ts"],
  synchronize: false,
  logging: true,
});
