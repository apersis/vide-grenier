describe("cycle d'un article", () => {
    beforeEach(() => {
      cy.visit('http://127.0.0.1:8000/login')
    })
    

    it("L'utilisateur se connecte et crée un article, le trouve sur la page d'accueil", () => {
        const email = 'admin@test.fr'
        const passeword = 'admin'
    
        cy.get('input#exampleInputEmail1.form-control').type(`${email}`)
        cy.get('input#exampleInputPassword1.form-control').type(`${passeword}`)

        const connection = cy.get('button.btn.btn-cv1').eq(0)
        connection.click()

        cy.url().should('eq', 'http://127.0.0.1:8000/account')

        cy.get('button#dropdownMenu1.btn.btn-default.dropdown-toggle').contains(`admin`)

        const ajouter = cy.get('a.deposer').eq(0)
        ajouter.click()

        cy.url().should('eq', 'http://127.0.0.1:8000/product')

        cy.get('input#titre.form-control.validate').type(`cypress`)
        cy.get('textarea#description.form-control.validate').type(`testCypress`)

        const filePath = 'logo cesi.png';
        cy.get('input#e4.form-control.validate').attachFile(filePath);

        const valider = cy.get('button.btn.btn-primary.u-btn').eq(0)
        valider.click()

        cy.url().should('match', /http:\/\/127\.0\.0\.1:8000\/product\/\d+/)

        const account = cy.get('button#dropdownMenu1.btn.btn-default.dropdown-toggle').eq(0)
        account.click()

        const accueil = cy.get('.navbar-brand').eq(0)
        accueil.click()

        cy.get('div#articlelist.row').contains(`testCypress`)
        const retourAlArticle = cy.get('div#articlelist.row').contains(`cypress`)
        retourAlArticle.click()

        const supprimer = cy.get('input#buttonDelete.btn.btn-primary.suppr-btn').eq(0)
        supprimer.click()

        cy.url().should('eq', 'http://127.0.0.1:8000')

        cy.get('div#articlelist.row').should('not.contain', 'cypress')
    })

})