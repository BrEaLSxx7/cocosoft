let date = [];
let name = document.getElementById('nombre');
let cc = document.getElementById('cc');
let sexo = document.getElementById('sexo');
let grupo = document.getElementById('grupo');

function add() {
	names = name.value;
	ccs = cc.value;
	sexos = parseInt(sexo.value);
	grupos = grupo.value;
	date.push({
		nombre: names,
		cc: ccs,
		fk_sexo_id: sexos,
	});
	conf = confirm('Desea agregar otra persona al grupo');
	if (conf) {
		document.getElementById('nombre').value = '';
		document.getElementById('cc').value = '';
		document.getElementById('sexo').value = '';
	} else {
		document.getElementById('form').reset();
		// fetch('http://localhost:8000/Asistente', {
		// 		body: {
		// 			date,
		// 			grupo: grupos
		// 		},
		// 		method: 'POST',
		// 		headers: {
		// 			"Content-Type": "application/json"
		// 		}
		// 	})
		// 	.then(response => console.log(response))
		// 	.catch(erro => console.log(erro));
		$.ajax({
			url: 'http://localhost:8000/Asistente',
			data: {
				date: date,
				grupo: grupos
			},
			dataType: 'json',
			method: 'POST'
		}, (response) => {
			console.log(response);
		}, (err) => {
			console.error(err)
		});

		date = [];
	}
}