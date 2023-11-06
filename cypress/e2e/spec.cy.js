describe('Test utilisateur', () => {
  it('ajout de compte', () => {
    cy.visit('https://localhost:8000/register')
    cy.get('#register_firstname').type("Mathieu")
    cy.get('#register_name').type("Mithridate")
    cy.get('#register_email').type("mathieu.mith@laposte.net")
    cy.get('#register_password_first').type("1234")
    cy.get('#register_password_second').type("1234")
    cy.get('#register_Ajouter').click()
  })
})