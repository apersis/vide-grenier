describe('Page Account', () => {
    beforeEach(() => {
        cy.setCookie('session_id', 'your_session_id_here'); 
        cy.visit('http://127.0.0.1:8000/account');
    });

    it('Le titre est bien affiché', () => {
        cy.contains("");
    });

    it("L'utilisateur est connecté et la page du compte est affichée", () => {
        cy.get('button#dropdownMenu1.btn.btn-default.dropdown-toggle')
          .contains('');
    });
});
