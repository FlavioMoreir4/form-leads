//fbq('track', 'ViewContent'); //FACEBOOK PIXEL
let getParams = function (e) {
        for (var t = window.location.search.substring(1).split("&"), a = 0; a < t.length; a++) {
            var n = t[a].split("=");
            if (n[0] == e) return n[1]
        }
        return !1
    },
    Consult = function (e, t) {
        return fetch(e, {
            method: "POST",
            body: t,
            headers: {
                "Content-type": "application/x-www-form-urlencoded"
            }
        })
    },
    Insert = function (e, t) {
        return fetch(e, {
            method: "POST",
            body: t
        })
    };
const scriptURL = "https://script.google.com/macros/s/AKfycbx2ppF6yODMLFUa_jQW2CrbMEeXew187g3G1uRObM9CeQilOcI/exec",
    URL = "https://ipadraomilitar.com.br/api/ajax.php",
    form = document.forms.form,
    franquia = "%%franquia%%".toUpperCase(),
    whatsappGeral = "%%whatsapp%%";
let whatsappCosultor, menssagem, regiao = form.elements.regiao,
    regiaoText = document.getElementById("regiao_text"),
    responsavel = form.elements.responsavel,
    candidato = form.elements.candidato,
    whatsappCosult = form.elements.whatsapp,
    btnEnviar = form.elements.btnEnviar,
    campanha = getParams("campanha") ? form.elements.campanha.value = getParams("campanha") : form.elements.campanha
    .value,
    r = getParams("r") ? getParams("r") : "geral";
var cleave = new Cleave("#whatsapp", {
    prefix: "",
    delimiters: [" ", "-"],
    blocks: [2, 5, 4],
    uppercase: !0
});

function Check() {
    whatsappCosult.setCustomValidity(""), whatsappCosult.value.length >= 13 && Consult(URL, "franquia=" + franquia
        .toLowerCase() + "&wpp=" + whatsappCosult.value).then(e => e.json()).then(function (e) {
        console.log(e), 0 === e.length ? (whatsappCosult.setCustomValidity(""), btnEnviar.disabled = !1,
            btnEnviar.hidden = !1) : (whatsappCosult.setCustomValidity(
                "Este WhatsApp já está cadastrado, em breve um consultor entrarar em contado"),
            whatsappCosult.reportValidity(), btnEnviar.disabled = !0, btnEnviar.hidden = !0)
    })
}

function preSuccess() {
    regiaoText.innerText = "em " + regiao.value, form.classList.add("is-hidden"), document.querySelector(".process")
        .classList.remove("is-hidden")
}

function Success(e) {
    /*fbq("track", "Lead"), fbq("track", "CompleteRegistration", {
        value: 0,
        currency: "BRL"
    }),*/
    Consult(URL, "phonesCons=" + regiao.value + "&franquia=" + franquia).then(e => e.json()).then(function (
        e) {
        if (console.log(e.length), 0 == e.length) whatsappCosultor = whatsappGeral;
        else
            for (var t = 0; t < e.length; t++) whatsappCosultor = e[t].telefone;
        menssagem = responsavel ? "*EU QUERO*\nCandidato: " + candidato.value + "\nResponsável: " +
            responsavel.value + "\nRegião: " + regiao.value : "*EU QUERO*\nCandidato: " + candidato.value +
            "\nRegião: " + regiao.value, Loader(20)
    })
}
Consult(URL, "r=" + r).then(e => e.json()).then(function (e) {
    for (var t = 0; t < e.length; t++) {
        var a = document.createElement("option");
        a.text = e[t].nome, a.value = e[t].nome, regiao.appendChild(a), 1 == e.length && (regiao
            .selectedIndex = 1, document.getElementById("regG").style.display = "none"), console.log(e[
            t].nome)
    }
}), form.addEventListener("submit", e => {
    btnEnviar.disabled = !0, e.preventDefault(), preSuccess(), Insert(URL, new FormData(form)).then(e => e)
        .then(function (e) {
            Insert(scriptURL, new FormData(form)).then(e => e).then(function (e) {
                Success(e)
            }).catch(e => console.error("SpreadSheets: " + e))
        }).catch(e => console.error(e))
});
const makeLink = function () {
    ! function () {
        let e = document.createElement("a");
        e.href = function () {
                let e = "https://wa.me/";
                return whatsappCosultor && "" !== whatsappCosultor.value && (e +=
                    `${encodeURIComponent(whatsappCosultor)}`), menssagem && "" !== menssagem.value && (e +=
                    `?text=${encodeURIComponent(menssagem)}`), e
            }(), e.innerHTML = '<i class="fab fa-whatsapp"></i> Abrir WhatsApp', document.querySelector(".load")
            .style.display = "none", document.querySelector(".process").append(e), e.click()
    }()
};

function Loader(e) {
    document.querySelector(".progress-pie-chart");
    let t = 0,
        a = setInterval(function () {
            t >= 100 ? (clearInterval(a), makeLink()) : t++
        }, e)
}