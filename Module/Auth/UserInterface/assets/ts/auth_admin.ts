function bindAuth(baseUri: string) {
    $('.register-webauth').on('click',function() {
        if (!navigator.credentials) {
            return;
        }
        postJson(baseUri + '/passkey/register_option', {}, res => {
            const data = res.data;
            data.challenge = Base64.toBuffer(data.challenge);
            data.user.id = Base64.toBuffer(data.user.id);
            navigator.credentials.create({
                publicKey: data
            })
            .then((credential: any) => {
                const response = credential.response as AuthenticatorAttestationResponse;
                postJson(baseUri + '/passkey/register', {credential: {
                    id: credential.id,
                    clientDataJSON: Base64.encode(response.clientDataJSON),
                    attestationObject: Base64.encode(response.attestationObject),
                    publicKeyAlgorithm: response.getPublicKeyAlgorithm(),
                    // transports: response.getTransports(),
                    // authenticatorData: Base64.encode(response.getAuthenticatorData())
                }});
            })
            .catch(console.error);
        });
        
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
