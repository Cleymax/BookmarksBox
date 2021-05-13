FROM node:14-alpine

RUN mkdir -m 777 /.npm
RUN mkdir -m 777 /.yarn
RUN touch /.yarnrc && chmod 777 /.yarnrc

RUN npm install

CMD ["npm", "run", "build"]
