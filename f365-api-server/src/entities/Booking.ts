import { Entity, PrimaryGeneratedColumn, Column } from "typeorm";

@Entity("booking")
export class Booking {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ type: "varchar", length: 255 })
  doctorName: string;

  @Column({ type: "date" })
  date: string;

  @Column({ type: "int" })
  hour: number;

  @Column({ type: "varchar", length: 255 })
  patientFullName: string;

  @Column({ type: "text" })
  comments: string;

  @Column({ type: "boolean", default: false })
  deleted: boolean;
}
