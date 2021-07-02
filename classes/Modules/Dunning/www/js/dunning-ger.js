var dunningAssistantGerman = [{
    type: 'defaultPage',
    icon: 'error',
    headline: 'Leider konnten wir Deine Zahlung<br> nicht verarbeiten.',
    text: '<div class="center">Dein Konto ist weiterhin aktiv, wir bitten Dich aber um Klärung.<br> Bitte kontaktiere uns unter <a href="mailto:kontakt@xentral.com">kontakt@xentral.com</a>.</div>',
    ctaButtons: [{
        title: 'Weiter',
        action: 'close'
    }]
}];

new Vue({
    el: '#dunning-assistant',
    data: {
        showAssistant: true,
        pagination: false,
        allowClose: true,
        pages: dunningAssistantGerman
    }
});
