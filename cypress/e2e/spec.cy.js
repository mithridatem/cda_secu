describe('Test utilisateur', () => {
  it('Modification du compte existe', () => {
    cy.visit('https://localhost:8000/register/update/1')
    cy.get('#register_firstname').clear().type("Mathieu2")
    cy.get('#register_name').clear().type("Mithridate2")
    cy.get('#register_email').clear().type("mathieu.mith@laposte.net")
    cy.get('#register_password_first').clear().type("1234")
    cy.get('#register_password_second').clear().type("1234")
    cy.get('#register_Ajouter').click()
    cy.get('strong').invoke("text").then((text)=>{
      let dateTest = new Date();
      dateTest = dateTest.getFullYear()+"-"+(dateTest.getMonth()+1)+"-"+dateTest.getDate();
      let statut = true;
      const url = "https://localhost:8000/tests/validation";
      if(text=="Le compte a été mis à jour en BDD"){
        statut = true;
      }
      else{
        statut = false;
      }
      const json = JSON.stringify({"title":"Modification du compte existe","date":dateTest,
        "statut":statut});
      cy.request({
        method: "POST",
        url: url,
        body:json
      }).then((resp) => {
        if(expect(resp.status).to.eq(200)){
          cy.log("enregistrement ok")
        }else{
          cy.log('Le fichier json est incorrect')
        }
      })
    })
  })
  it('Modification du compte n\'existe pas', () => {
    cy.visit('https://localhost:8000/register/update/1')
    cy.get('strong').should("contain", "le compte n'existe pas")
  })
})