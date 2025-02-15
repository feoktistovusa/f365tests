import "reflect-metadata";
import express from "express";
import { ApolloServer } from "@apollo/server";
import { expressMiddleware } from "@apollo/server/express4";
import cors from "cors";
import bodyParser from "body-parser";
import { AppDataSource } from "./config/data-source";
import { typeDefs } from "./graphql/typeDefs";
import { resolvers } from "./graphql/resolvers";

const app = express();

async function startServer() {
  await AppDataSource.initialize();
  console.log("📦 Database connected");

  const server = new ApolloServer({
    typeDefs,
    resolvers,
    csrfPrevention: false,
    introspection: true,
  });

  await server.start();

  app.use(
    "/graphql",
    cors(),
    bodyParser.json(),
    // @ts-ignore
    expressMiddleware(server),
  );

  const PORT = process.env.PORT || 3000;
  app.listen(PORT, () => {
    console.log(`🚀 Server ready at http://localhost:${PORT}/graphql`);
  });
}

startServer();
