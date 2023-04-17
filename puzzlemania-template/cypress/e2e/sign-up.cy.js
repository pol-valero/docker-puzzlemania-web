describe("Sign up", () => {
    before(() => {
        cy.recreateDatabase();
    });
    it("[SU-1] shows the sign-up page", () => {
        cy.visit("/sign-up");
        cy.get(`[data-cy="sign-up"]`).should("exist");
        cy.get(`[data-cy="sign-up__email"]`).should("exist");
        cy.get(`[data-cy="sign-up__password"]`).should("exist");
    });

    it("[SU-2] allows the user to sign-up correctly", () => {
        cy.visit("/sign-up");
        cy.get(`[data-cy="sign-up__email"]`).type("student@salle.url.edu");
        cy.get(`[data-cy="sign-up__password"]`).type("Test001");
        cy.get(`[data-cy="sign-up__repeatPassword"]`).type("Test001");
        cy.get(`[data-cy="sign-up__btn"]`).click();
        cy.location('pathname').should('eq', '/sign-in')
    });

    it("[SU-3] shows error when email does not have salle.url.edu", () => {
        cy.visit("/sign-up");
        cy.get(`[data-cy="sign-up__email"]`).type("student@gmail.com");
        cy.get(`[data-cy="sign-up__password"]`).type("Test001");
        cy.get(`[data-cy="sign-up__repeatPassword"]`).type("Test001");
        cy.get(`[data-cy="sign-up__btn"]`).click();
        cy.get(`[data-cy="sign-up__wrongEmail"]`).should("exist");
        cy.get(`[data-cy="sign-up__wrongEmail"]`).invoke('text').should("eq", "Only emails from the domain @salle.url.edu are accepted.");
    });

    it("[SU-4] shows error when email is not a valid email", () => {
        cy.visit("/sign-up");
        cy.get(`[data-cy="sign-up__email"]`).type("student");
        cy.get(`[data-cy="sign-up__password"]`).type("Test001");
        cy.get(`[data-cy="sign-up__repeatPassword"]`).type("Test001");
        cy.get(`[data-cy="sign-up__btn"]`).click();
        cy.get(`[data-cy="sign-up__wrongEmail"]`).should("exist");
        cy.get(`[data-cy="sign-up__wrongEmail"]`).invoke('text').should("eq", "The email address is not valid.");
    });

    it("[SU-5] shows error when password has less than 7 characters", () => {
        cy.visit("/sign-up");
        cy.get(`[data-cy="sign-up__email"]`).type("student@salle.url.edu");
        cy.get(`[data-cy="sign-up__password"]`).type("Test");
        cy.get(`[data-cy="sign-up__repeatPassword"]`).type("Test");
        cy.get(`[data-cy="sign-up__btn"]`).click();
        cy.get(`[data-cy="sign-up__wrongPassword"]`).should("exist");
        cy.get(`[data-cy="sign-up__wrongPassword"]`).invoke('text').should("eq", "The password must contain at least 7 characters.");
    });

    it("[SU-6] shows error when password does not follow correct format", () => {
        cy.visit("/sign-up");
        cy.get(`[data-cy="sign-up__email"]`).type("student@salle.url.edu");
        cy.get(`[data-cy="sign-up__password"]`).type("TestTest");
        cy.get(`[data-cy="sign-up__repeatPassword"]`).type("TestTest");
        cy.get(`[data-cy="sign-up__btn"]`).click();
        cy.get(`[data-cy="sign-up__wrongPassword"]`).should("exist");
        cy.get(`[data-cy="sign-up__wrongPassword"]`).invoke('text').should("eq", "The password must contain both upper and lower case letters and numbers.");
    });

    it("[SU-7] shows error when passwords do not match", () => {
        cy.visit("/sign-up");
        cy.get(`[data-cy="sign-up__email"]`).type("student@salle.url.edu");
        cy.get(`[data-cy="sign-up__password"]`).type("TestTest");
        cy.get(`[data-cy="sign-up__repeatPassword"]`).type("Test");
        cy.get(`[data-cy="sign-up__btn"]`).click();
        cy.get(`[data-cy="sign-up__wrongPassword"]`).should("exist");
        cy.get(`[data-cy="sign-up__wrongPassword"]`).invoke('text').should("eq", "Passwords do not match.");
    });
});
