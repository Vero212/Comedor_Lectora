document.addEventListener('DOMContentLoaded', function () {
    limpiar();

    const tarjetaInput = document.getElementById('tarjeta');
    const mensaje = document.getElementById('mensaje');

    tarjetaInput.disabled = false;
    tarjetaInput.focus();
    mensaje.style.color = 'black';
    mensaje.textContent = '--> Acerque su Tarjeta al Lector <--';

    setInterval(() => tarjetaInput.focus(), 4000);

    tarjetaInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('temp').focus();

		
		
		const nroTarjeta = document.getElementById('tarjeta').value;
            fetch(`${window.location.origin}/comedor/public/ingreso/validar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                //body: new URLSearchParams(new FormData(document.getElementById('form-val')))
			body: `tarjeta=${encodeURIComponent(nroTarjeta)}`
            })
            .then(response => response.json())
            .then(data => {
                tarjetaInput.disabled = true;

                if (data.error > 0) {
                    mensaje.style.color = 'red';
                    mensaje.textContent = data.result;

                    setTimeout(() => limpiar(), 2000);
                } else {
                    imprSelec(data.id_ticket);

                    document.getElementById('cont-msg').style.display = 'none';
                    document.getElementById('bienvenida').textContent = `Bienvenido ${data.nombre}`;
                    document.getElementById('cont-foto').style.display = 'block';

                    setTimeout(() => limpiar(), 3500);
                }
            })
            .catch(err => {
                mensaje.style.color = 'red';
                mensaje.textContent = 'Error de conexión';
                setTimeout(() => limpiar(), 3000);
                console.error(err);
            });
        }
    });

    // Evita envío accidental con enter en otro input
    document.querySelectorAll('.noEnterSubmit').forEach(input => {
        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') e.preventDefault();
        });
    });
});

function limpiar() {
    const tarjeta = document.getElementById('tarjeta');
    tarjeta.disabled = false;
    tarjeta.value = '';

    document.getElementById('cont-foto').style.display = 'none';
    document.getElementById('footer').style.display = 'block';
    document.getElementById('cont-msg').style.display = 'block';

    const mensaje = document.getElementById('mensaje');
    mensaje.style.color = 'black';
    mensaje.textContent = '--> Acerque su Tarjeta al Lector <--';

    document.getElementById('genTicket').src = '';
    document.getElementById('temp').value = '';
    document.getElementById('bienvenida').textContent = '';

    tarjeta.focus();
}

function imprSelec(nroticket) {
    fetch(`${window.location.origin}/comedor/public/ingreso/generarTicket/${nroticket}`)
        .then(() => {
            console.log('Ticket enviado a impresión');
        })
        .catch(err => {
            console.error('Error al imprimir ticket', err);
        });
}