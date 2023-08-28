import { fakerFA } from "@faker-js/faker";

describe("UserLogin", () => {
  let phoneNumber = "0930" + Math.floor(Math.random() * 1e6) + 1;
  let name = fakerFA.person.firstName();
  let family = fakerFA.person.lastName();
  let sex = fakerFA.person.sex();
  cy.login(phoneNumber, name, family, sex)
});
