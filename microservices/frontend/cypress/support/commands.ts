/// <reference types="cypress" />
// ***********************************************
// This example commands.ts shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
//
export { }
declare global {
  namespace Cypress {
    interface Chainable {
      login(phoneNumber: string, name: string, family: string, sex: string): Chainable<void>
    
    }
  }
}

Cypress.Commands.add("login", (phoneNumber: string, name: string, family: string, sex: string) => {
    cy.visit("auth/login");
    cy.get("input[name=phone]").type(phoneNumber);
    cy.get("button.btn-primary").contains("ورود").click();
    cy.intercept("request").as("request");
    cy.wait("@request").then((res) => {
        expect(res.response?.statusCode).to.equal(200);
        let code = res.response?.body.verification.code;
        if (res.response?.body.verification.is_new) {
            cy.get("input[name=firstName]").type(name);
            cy.get("input[name=lastName]").type(family);
            sex === "female"
                ? cy.get("label[for='radio-1']").click()
                : cy.get("label[for='radio-0']").click();
        }
        cy.get("div.otp-inputs > input:first-child").type(code);
    });
    cy.get("button[type='submit']").click({ force: true });
    cy.intercept("verify").as("verify");
    cy.wait("@verify").then((res) => {
        expect(res.response?.statusCode).to.equal(200);
        cy.session("auth", () => {
            localStorage.setItem(
                "auth",
                JSON.stringify({
                    access_token: res.response?.body.access_token,
                    refresh_token: res.response?.body.refresh_token,
                    expires_in: res.response?.body.expires_in,
                })
            );
        });
    });
})