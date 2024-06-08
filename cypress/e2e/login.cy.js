describe('example to-do app', () => {
    beforeEach(() => {
      cy.visit('http://127.0.0.1:8000/login')
    })
    
    it('Le titre est bien affiché', () => {
        cy.contains("Des centaines d'articles vous attendent")
    })

    it('Les champs email et password sont remplis, le bouton connection est cliqué, la connection à réussi', () => {
        const email = 'admin@test.fr'
        const passeword = 'admin'
    
        cy.get('input#exampleInputEmail1.form-control').type(`${email}`)
        cy.get('input#exampleInputPassword1.form-control').type(`${passeword}`)

        const connection = cy.get('button.btn.btn-cv1').eq(0)
        connection.click()

        cy.url().should('eq', 'http://127.0.0.1:8000/account')

        cy.get('button#dropdownMenu1.btn.btn-default.dropdown-toggle').contains(`admin`)

    })

})