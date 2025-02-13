FROM node:23.7.0-alpine3.21 as dev

RUN npm i npm@latest -g

ARG NODE_ENV=production
ENV NODE_ENV $NODE_ENV

ARG PORT=3000
ENV PORT $PORT
EXPOSE $PORT

WORKDIR /app

FROM dev as prod

COPY --chown=node:node . /app
RUN mkdir -p /app/node_modules && chown -R node:node /app/node_modules

USER node

RUN npm install --omit=optional
RUN npx tsc
RUN npm run build

CMD ["npm", "run", "start"]
