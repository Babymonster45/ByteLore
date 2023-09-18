# @ByteLore

## Development Setup

When installing the tools needed for development see the [tools section](#tools) to see which versions to use.

* Install [node](https://nodejs.org) using [nvm](https://github.com/nvm-sh/nvm) (for Mac).
* Install [yarn](https://yarnpkg.com) using node.
  - `npm i -g yarn`
* Install [next](https://nextjs.org/docs/getting-started/installation) using yarn.
  - `yarn global add nx`
* Change directories into the root of this repository.
* Install dependencies with yarn
  - `yarn`

## Scripts (do not try these yet)

* `yarn build`: Lints, tests, and builds all projects. Use prior to pushing changes to make sure nothing is broken.
* `yarn gen:graphql`: Generate frontend GraphQL types based on running APIs.
* `yarn graph`: See a diagram of the dependencies of the projects.
* `yarn start`: Run `api` & `web` projects concurrently.

## Tools

Here is a list of tools used to work on this repository.
| Tool                                   | Version |
|----------------------------------------|---------|
| [next](https://nextjs.org/)            | 13.4.1  |
| [node](https://nodejs.org)             | 16.19.1 |
| [npm](https://nodejs.org)              | 9.8.1   |
| [yarn](https://yarnpkg.com)            | 1.22.19 |


## Formation
This repository was generated using [create-nx-workspace](https://nextjs.org/docs/getting-started/installation) with the following command:
```bash
npx create-next-app@latest
```
