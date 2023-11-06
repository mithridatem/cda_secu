describe('Test utilisateur', () => {
  it('Modification du compte existe', () => {
    cy.visit('https://localhost:8000/register/update/2')
    cy.get('#register_firstname').clear().type("Mathieu2")
    cy.get('#register_name').clear().type("Mithridate2")
    cy.get('#register_email').clear().type("mathieu.mith@laposte.net")
    cy.get('#register_password_first').clear().type("1234")
    cy.get('#register_password_second').clear().type("1234")
    cy.get('#register_Ajouter').click()
    cy.get('strong').should("contain", "Le compte a été mis à jour en BDD")
  })
  it('Modification du compte n\'existe pas', () => {
    cy.visit('https://localhost:8000/register/update/1')
    cy.get('strong').should("contain", "le compte n'existe pas")
  })
})