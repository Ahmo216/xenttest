var dunningAssistantEnglish = [{
    type: 'defaultPage',
    icon: 'error',
    headline: 'Unfortunately we have problems<br> processing your payment.',
    text: '<div class="center">Your account is still active,<br> but we kindly ask you to get in touch with us for clarification.' +
        '<br>Please write us at <a href="mailto:kontakt@xentral.com">kontakt@xentral.com</a></div>',
    ctaButtons: [{
        title: 'Continue',
        action: 'close'
    }]
}];

new Vue({
    el: '#dunning-assistant',
    data: {
        showAssistant: true,
        pagination: false,
        allowClose: true,
        pages: dunningAssistantEnglish
    }
});
