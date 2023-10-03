function bindAuth(id: number) {
    const credentialCreationOptionsRegister: CredentialCreationOptions = {
        publicKey: {
            rp: {
                name: 'difi',
            },
            user: {
                name: 'difi',
                id: new Uint8Array(16),
                displayName: 'difi',
            },
            pubKeyCredParams: [{
                    type: 'public-key',
                    alg: -7,
                },
                {
                    type: 'public-key',
                    alg: -36,
                },
                {
                    type: 'public-key',
                    alg: -257,
                },
            ],
            challenge: new Uint8Array(16),
            timeout: 60 * 1000,
        },
    };
    $('.register-webauth').on('click',function() {
        if (!navigator.credentials) {
            return;
        }
        navigator.credentials.create(credentialCreationOptionsRegister)
            .then((credentials) => {
                console.log(credentials);
            })
            .catch(console.error);
    }).toggle(!!navigator.credentials);
}

function bindBulletin() {
    $(document).on('click', '.bulletin-item .title', function() {
        $(this).closest('.bulletin-item').toggleClass('min');
    }).on('click', '.bulletin-item .content a', function(e) {
        let $this = $(this);
        if ($this.attr('target')) {
            return;
        }
        const url = $this.attr('href');
        if (url === '#' || !url || url.indexOf('javascript:') >= 0) {
            return;
        }
        e.stopPropagation();
        e.preventDefault();
        window.location.href = $this.attr('href');
    });
}
