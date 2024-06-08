describe('example to-do app', () => {
    beforeEach(() => {
      cy.visit('http://127.0.0.1:8000/register')
    })
    
    it('Le titre est bien affiché', () => {
        cy.contains("Des centaines d'articles vous attendent")
    })

    it('Le sous-titre est bien affiché', () => {
        cy.contains("Plus de 15 articles ajoutés quotidiennement")
    })

    it('Les champs email et password sont remplis, le bouton connection est cliqué, la connection à réussi', () => {
        const nom = 'cypress'
        const email = 'cypress@test.fr'
        const CP = '29490'
        const passeword = 'testcypress'
    
        cy.get('input#username.form-control').type(`${nom}`)
        cy.get('input#exampleInputEmail1.form-control').type(`${email}`)
        cy.get('input#codepostalregister.form-control.cityAutoComplete').type(`${CP}`)
        cy.get('select#villeInput.form-control').contains('Guipavas')
        cy.get('input#exampleInputPassword1.form-control').type(`${passeword}`)
        cy.get('input#exampleInputPassword2.form-control').type(`${passeword}`)

        const connection = cy.get('button.btn.btn-cv1').eq(0)
        connection.click()

        cy.url().should('eq', 'http://127.0.0.1:8000/account')

        cy.get('button#dropdownMenu1.btn.btn-default.dropdown-toggle').contains(`cypress`)

    })

})