describe('Json', ()=>{
    before(()=>{
        cy.fixture("user.json").then(function(data){
            this.data = data.user
        })
    })
    it("Boucle json", function(){
        this.data.forEach((user)=>{
            cy.log(user.name)
            cy.log(user.firstname)
            cy.log(user.email)
            cy.log(user.password)
        })
    })
})