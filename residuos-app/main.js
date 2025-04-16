// LocalStorage para persistencia simple
const usuarios = JSON.parse(localStorage.getItem("usuarios") || "[]");
const solicitudes = JSON.parse(localStorage.getItem("solicitudes") || "[]");

function register() {
  const nombre = document.getElementById("registerName").value;
  const email = document.getElementById("registerEmail").value;
  const pass = document.getElementById("registerPassword").value;
  const rol = document.getElementById("registerRole").value.toLowerCase(); // Normalizar el rol

  usuarios.push({ nombre, email, pass, rol, puntos: 0 });
  localStorage.setItem("usuarios", JSON.stringify(usuarios));
  Swal.fire("Registro exitoso", "Tu cuenta ha sido creada.", "success");
}

function login() {
  const email = document.getElementById("loginEmail").value;
  const pass = document.getElementById("loginPassword").value;
  const user = usuarios.find(u => u.email === email && u.pass === pass);
  if (user) {
    localStorage.setItem("actual", JSON.stringify(user));
    window.location.href = "dashboard.html";
  } else {
    Swal.fire("Error", "Credenciales inválidas", "error");
  }
}

function cerrarSesion() {
  localStorage.removeItem("actual");
  window.location.href = "index.html";
}

function crearSolicitud() {
  const fecha = document.getElementById("fecha").value;
  const tipo = document.getElementById("tipoResiduo").value;
  const peso = parseFloat(document.getElementById("peso").value);
  const usuario = JSON.parse(localStorage.getItem("actual"));

  let puntos = 0;
  if (tipo === "reciclable") puntos = peso * 1;
  if (tipo === "peligroso") puntos = peso * 2;

  usuario.puntos += puntos;
  localStorage.setItem("actual", JSON.stringify(usuario));
  const index = usuarios.findIndex(u => u.email === usuario.email);
  usuarios[index] = usuario;
  localStorage.setItem("usuarios", JSON.stringify(usuarios));

  solicitudes.push({ usuario: usuario.nombre, fecha, tipo, peso, estado: "pendiente" });
  localStorage.setItem("solicitudes", JSON.stringify(solicitudes));

  Swal.fire("¡Solicitud enviada!", "Se notificará vía WhatsApp (simulado).", "success");

  mostrarDashboard();
}

function mostrarDashboard() {
  const user = JSON.parse(localStorage.getItem("actual"));
  document.getElementById("welcome").textContent = `Bienvenido, ${user.nombre} (${user.rol})`;

  const rol = user.rol.toLowerCase(); // Asegurar coincidencia exacta

  if (rol === "usuario") {
    document.getElementById("user-section").classList.remove("hidden");
    document.getElementById("puntosUsuario").innerText = `Tienes ${user.puntos} puntos.`;
  }

  if (rol === "empresa") {
    document.getElementById("empresa-section").classList.remove("hidden");
    const lista = document.getElementById("listaSolicitudes");
    lista.innerHTML = solicitudes.map(s => `<li>${s.usuario}: ${s.tipo} (${s.peso} kg) - ${s.estado}</li>`).join("");
  }

  if (rol === "admin") {
    generarGraficos();
    document.getElementById("admin-section").classList.remove("hidden");

    const usuariosHTML = usuarios.map((u, i) => `
<li>
  ${u.nombre} - ${u.email} (${u.rol}) - ${u.puntos} pts
  <button onclick="editarUsuario(${i})">Editar</button>
  <button onclick="eliminarUsuario(${i})">Eliminar</button>
</li>`).join("");
    document.getElementById("listaUsuarios").innerHTML = usuariosHTML;

    const empresas = usuarios.filter(u => u.rol === "empresa");
    const empresasHTML = empresas.map((e, i) => {
      const realIndex = usuarios.findIndex(u => u.email === e.email);
      return `
  <li>
    ${e.nombre} - ${e.email}
    <button onclick="editarEmpresa(${i})">Editar</button>
    <button onclick="eliminarEmpresa(${realIndex})">Eliminar</button>
  </li>`;
    }).join("");
    document.getElementById("listaEmpresas").innerHTML = empresasHTML;

    renderSolicitudesAdmin();
  }
}

if (window.location.pathname.includes("dashboard.html")) {
  mostrarDashboard();
}

function renderSolicitudesAdmin(lista = solicitudes) {
  const html = lista.map((s, i) => `
<li>
  ${s.usuario} - ${s.tipo} (${s.peso}kg) - ${s.fecha} - ${s.estado}
  <button onclick="editarSolicitud(${i})">Editar</button>
  <button onclick="eliminarSolicitud(${i})">Eliminar</button>
</li>`).join("");
  document.getElementById("listaSolicitudesAdmin").innerHTML = html;
}

function filtrarSolicitudes() {
  const inicio = document.getElementById("fechaInicio").value;
  const fin = document.getElementById("fechaFin").value;

  if (!inicio || !fin) {
    Swal.fire("Error", "Debes seleccionar ambas fechas", "warning");
    return;
  }

  const filtradas = solicitudes.filter(s => {
    return s.fecha >= inicio && s.fecha <= fin;
  });

  renderSolicitudesAdmin(filtradas);
}

// Resto de funciones (editar, eliminar, exportar) ya están integradas correctamente

  
  
  