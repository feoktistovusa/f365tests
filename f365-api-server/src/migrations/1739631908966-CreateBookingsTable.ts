import { MigrationInterface, QueryRunner, Table } from "typeorm";

export class CreateBookingsTable1739631908966 implements MigrationInterface {
  public async up(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.createTable(
      new Table({
        name: "booking",
        columns: [
          {
            name: "id",
            type: "int",
            isPrimary: true,
            isGenerated: true,
            generationStrategy: "increment",
          },
          {
            name: "doctorName",
            type: "varchar",
            length: "255",
            isNullable: false,
          },
          {
            name: "date",
            type: "date",
            isNullable: false,
          },
          {
            name: "hour",
            type: "int",
            isNullable: false,
          },
          {
            name: "patientFullName",
            type: "varchar",
            length: "255",
            isNullable: false,
          },
          {
            name: "comments",
            type: "text",
            isNullable: false,
          },
          {
            name: "deleted",
            type: "boolean",
            default: false,
          },
        ],
      }),
      true,
    );
  }

  public async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.dropTable("booking");
  }
}
