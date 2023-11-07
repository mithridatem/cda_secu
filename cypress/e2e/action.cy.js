describe('Action Github',()=>{
    it('Ajouter un compte', ()=>{
        cy.visit("https://securite.adrardev.fr/register");
        cy.get('#register_firstname').type("Mathieu")
        cy.get('#register_name').type("Mithridate")
        cy.get('#register_email').type("mathieu.mith@laposte.net")
        cy.get('#register_password_first').type("1234")
        cy.get('#register_password_second').type("1234")
        cy.get('#register_Ajouter').click()
        cy.get('strong').should("contain", "Le compte : mathieu.mith@laposte.net a été ajouté en BDD")
    })
    it('Ajouter un compte doublon',()=>{
        cy.visit("https://securite.adrardev.fr/register");
        cy.get('#register_firstname').type("Mathieu")
        cy.get('#register_name').type("Mithridate")
        cy.get('#register_email').type("mathieu.mith@laposte.net")
        cy.get('#register_password_first').type("1234")
        cy.get('#register_password_second').type("1234")
        cy.get('#register_Ajouter').click()
        cy.get('strong').should("contain", "Le compte : mathieu.mith@laposte.net existe déja")
    })
})