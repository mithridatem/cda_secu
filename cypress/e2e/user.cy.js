describe('Json', ()=>{
    before(()=>{
        cy.fixture("user.json").then(function(data){
            this.data = data.users
        })
    })
    it("Boucle json", function(){
        this.data.forEach((user)=>{
            cy.visit('https://localhost:8000/register/update/2')
            cy.get('#register_firstname').clear().type(user.firstname)
            cy.get('#register_name').clear().type(user.name)
            cy.get('#register_email').clear().type(user.email)
            cy.get('#register_password_first').clear().type(user.password)
            cy.get('#register_password_second').clear().type(user.password)
            cy.get('#register_Ajouter').click()
            cy.get('strong').should("contain", "Le compte a été mis à jour en BDD")
        })
    })
})