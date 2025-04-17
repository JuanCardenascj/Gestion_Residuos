// ===================== MODELOS ===================== //

class Usuario {
  constructor(nombre, email, pass, rol) {
    this.nombre = nombre;
    this.email = email;
    this.pass = pass;
    this.rol = rol;
    this.puntos = 0;
  }
}

class EmpresaRecolectora extends Usuario {
  constructor(nombre, email, pass) {
    super(nombre, email, pass, "empresa");
  }
}

class Administrador extends Usuario {
  constructor(nombre, email, pass) {
    super(nombre, email, pass, "admin");
  }
}

// ===================== FACTORY ===================== //

class UsuarioFactory {
  static crearUsuario(nombre, email, pass, rol) {
    switch (rol) {
      case "empresa":
        return new EmpresaRecolectora(nombre, email, pass);
      case "admin":
        return new Administrador(nombre, email, pass);
      default:
        return new Usuario(nombre, email, pass, "usuario");
    }
  }
}

// ===================== STRATEGY ===================== //

class PuntosStrategy {
  calcular(peso) {
    return 0;
  }
}

class ReciclableStrategy extends PuntosStrategy {
  calcular(peso) {
    return peso * 1;
  }
}

class PeligrosoStrategy extends PuntosStrategy {
  calcular(peso) {
    return peso * 2;
  }
}

// ===================== OBSERVER (Simulado) ===================== //

class Notificador {
  static enviar(mensaje) {
    console.log("[Notificación WhatsApp]:", mensaje);
  }
}

// ===================== BASE DE DATOS LOCAL (Simulada) ===================== //

let usuarios = JSON.parse(localStorage.getItem("usuarios") || "[]");
let solicitudes = JSON.parse(localStorage.getItem("solicitudes") || "[]");

function guardarUsuarios() {
  localStorage.setItem("usuarios", JSON.stringify(usuarios));
}

function guardarSolicitudes() {
  localStorage.setItem("solicitudes", JSON.stringify(solicitudes));
}

// ===================== CONTROLADORES ===================== //

const AuthController = {
  register() {
    const nombre = document.getElementById("registerName").value;
    const email = document.getElementById("registerEmail").value;
    const pass = document.getElementById("registerPassword").value;
    const rol = document.getElementById("registerRole").value.toLowerCase();

    const nuevoUsuario = UsuarioFactory.crearUsuario(nombre, email, pass, rol);
    usuarios.push(nuevoUsuario);
    guardarUsuarios();
    Swal.fire("Registro exitoso", "Tu cuenta ha sido creada.", "success");
  },

  login() {
    const email = document.getElementById("loginEmail").value;
    const pass = document.getElementById("loginPassword").value;
    const user = usuarios.find(u => u.email === email && u.pass === pass);
    if (user) {
      localStorage.setItem("actual", JSON.stringify(user));
      window.location.href = "dashboard.html";
    } else {
      Swal.fire("Error", "Credenciales inválidas", "error");
    }
  },

  logout() {
    localStorage.removeItem("actual");
    window.location.href = "index.html";
  }
};

const SolicitudController = {
  crearSolicitud() {
    const fecha = document.getElementById("fecha").value;
    const tipo = document.getElementById("tipoResiduo").value;
    const peso = parseFloat(document.getElementById("peso").value);
    const usuario = JSON.parse(localStorage.getItem("actual"));

    let strategy;
    if (tipo === "reciclable") strategy = new ReciclableStrategy();
    else if (tipo === "peligroso") strategy = new PeligrosoStrategy();

    const puntosGanados = strategy.calcular(peso);
    usuario.puntos += puntosGanados;
    localStorage.setItem("actual", JSON.stringify(usuario));

    const index = usuarios.findIndex(u => u.email === usuario.email);
    usuarios[index] = usuario;
    guardarUsuarios();

    solicitudes.push({ usuario: usuario.nombre, fecha, tipo, peso, estado: "pendiente" });
    guardarSolicitudes();

    Notificador.enviar(`Nueva solicitud de ${usuario.nombre} para ${tipo} (${peso}kg)`);

    Swal.fire("¡Solicitud enviada!", "Se notificará vía WhatsApp (simulado).", "success");

    DashboardController.mostrar();
  },

  filtrar() {
    const inicio = document.getElementById("fechaInicio").value;
    const fin = document.getElementById("fechaFin").value;

    if (!inicio || !fin) {
      Swal.fire("Error", "Debes seleccionar ambas fechas", "warning");
      return;
    }

    const filtradas = solicitudes.filter(s => s.fecha >= inicio && s.fecha <= fin);
    DashboardController.renderSolicitudesAdmin(filtradas);
  }
};

const DashboardController = {
  mostrar() {
    const user = JSON.parse(localStorage.getItem("actual"));
    document.getElementById("welcome").textContent = `Bienvenido, ${user.nombre} (${user.rol})`;

    const rol = user.rol.toLowerCase();

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
      if (typeof generarGraficos === "function") generarGraficos();
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

      this.renderSolicitudesAdmin();
    }
  },

  renderSolicitudesAdmin(lista = solicitudes) {
    const html = lista.map((s, i) => `
<li>
  ${s.usuario} - ${s.tipo} (${s.peso}kg) - ${s.fecha} - ${s.estado}
  <button onclick="editarSolicitud(${i})">Editar</button>
  <button onclick="eliminarSolicitud(${i})">Eliminar</button>
</li>`).join("");
    document.getElementById("listaSolicitudesAdmin").innerHTML = html;
  }
};

if (window.location.pathname.includes("dashboard.html")) {
  DashboardController.mostrar();
}

// ===================== EVENTOS ===================== //

window.register = AuthController.register;
window.login = AuthController.login;
window.cerrarSesion = AuthController.logout;
window.crearSolicitud = SolicitudController.crearSolicitud;
window.filtrarSolicitudes = SolicitudController.filtrar;

// ===================== FUNCIONES ADMIN ===================== //

window.editarUsuario = function (index) {
  const usuario = usuarios[index];
  const nuevoNombre = prompt("Editar nombre:", usuario.nombre);
  if (nuevoNombre !== null) {
    usuarios[index].nombre = nuevoNombre;
    guardarUsuarios();
    DashboardController.mostrar();
  }
};

window.eliminarUsuario = function (index) {
  if (confirm("¿Estás seguro de eliminar este usuario?")) {
    usuarios.splice(index, 1);
    guardarUsuarios();
    DashboardController.mostrar();
  }
};

window.editarEmpresa = function (index) {
  const empresa = usuarios.filter(u => u.rol === "empresa")[index];
  const realIndex = usuarios.findIndex(u => u.email === empresa.email);
  const nuevoNombre = prompt("Editar nombre de empresa:", empresa.nombre);
  if (nuevoNombre !== null) {
    usuarios[realIndex].nombre = nuevoNombre;
    guardarUsuarios();
    DashboardController.mostrar();
  }
};

window.eliminarEmpresa = function (index) {
  if (confirm("¿Estás seguro de eliminar esta empresa?")) {
    usuarios.splice(index, 1);
    guardarUsuarios();
    DashboardController.mostrar();
  }
};

window.editarSolicitud = function (index) {
  const solicitud = solicitudes[index];
  const nuevoEstado = prompt("Editar estado de la solicitud:", solicitud.estado);
  if (nuevoEstado !== null) {
    solicitudes[index].estado = nuevoEstado;
    guardarSolicitudes();
    DashboardController.mostrar();
  }
};

window.eliminarSolicitud = function (index) {
  if (confirm("¿Estás seguro de eliminar esta solicitud?")) {
    solicitudes.splice(index, 1);
    guardarSolicitudes();
    DashboardController.mostrar();
  }
};

// ===================== FUNCIONES CLAVE ===================== //

function generarGraficos() {
  // Gráfico de Roles
  const ctxRoles = document.getElementById("graficoRoles").getContext("2d");
  const conteoRoles = usuarios.reduce((acc, u) => {
    acc[u.rol] = (acc[u.rol] || 0) + 1;
    return acc;
  }, {});

  new Chart(ctxRoles, {
    type: "bar",
    data: {
      labels: Object.keys(conteoRoles),
      datasets: [{
        label: "Cantidad de Usuarios por Rol",
        data: Object.values(conteoRoles),
        backgroundColor: ["#4caf50", "#2196f3", "#ff9800"]
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  });

  // Gráfico de Puntos
  const ctxPuntos = document.getElementById("graficoPuntos").getContext("2d");
  new Chart(ctxPuntos, {
    type: "pie",
    data: {
      labels: usuarios.map(u => u.nombre),
      datasets: [{
        label: "Puntos por Usuario",
        data: usuarios.map(u => u.puntos),
        backgroundColor: usuarios.map(() => `hsl(${Math.random() * 360}, 70%, 70%)`)
      }]
    }
  });

  // Gráfico de Solicitudes por tipo
  const ctxSolicitudes = document.getElementById("graficoSolicitudes").getContext("2d");
  const conteoTipos = solicitudes.reduce((acc, s) => {
    acc[s.tipo] = (acc[s.tipo] || 0) + 1;
    return acc;
  }, {});

  new Chart(ctxSolicitudes, {
    type: "doughnut",
    data: {
      labels: Object.keys(conteoTipos),
      datasets: [{
        label: "Solicitudes por Tipo",
        data: Object.values(conteoTipos),
        backgroundColor: ["#8bc34a", "#f44336"]
      }]
    }
  });
}

window.exportarUsuarios = function () {
  const data = JSON.stringify(usuarios, null, 2);
  descargarArchivo(data, "usuarios.json");
};

window.exportarSolicitudes = function () {
  const data = JSON.stringify(solicitudes, null, 2);
  descargarArchivo(data, "solicitudes.json");
};

function descargarArchivo(contenido, nombreArchivo) {
  const blob = new Blob([contenido], { type: "application/json" });
  const enlace = document.createElement("a");
  enlace.href = URL.createObjectURL(blob);
  enlace.download = nombreArchivo;
  enlace.click();
  URL.revokeObjectURL(enlace.href);
}

// ===================== VALIDACIONES ===================== //

function validarCamposRegistro(nombre, email, pass) {
  if (!nombre || !email || !pass) {
    Swal.fire("Campos incompletos", "Por favor completa todos los campos de registro.", "warning");
    return false;
  }
  if (!email.includes("@") || !email.includes(".")) {
    Swal.fire("Correo inválido", "Por favor ingresa un correo válido.", "warning");
    return false;
  }
  if (pass.length < 4) {
    Swal.fire("Contraseña débil", "La contraseña debe tener al menos 4 caracteres.", "warning");
    return false;
  }
  return true;
}

function validarCamposLogin(email, pass) {
  if (!email || !pass) {
    Swal.fire("Campos vacíos", "Ingresa tu correo y contraseña.", "warning");
    return false;
  }
  return true;
}

function validarSolicitud(fecha, peso) {
  if (!fecha || !peso || peso <= 0) {
    Swal.fire("Datos inválidos", "Debes ingresar una fecha válida y un peso mayor a 0.", "warning");
    return false;
  }
  return true;
}

// ===================== ACTUALIZACIÓN DE CONTROLADORES ===================== //

AuthController.register = function () {
  const nombre = document.getElementById("registerName").value;
  const email = document.getElementById("registerEmail").value;
  const pass = document.getElementById("registerPassword").value;
  const rol = document.getElementById("registerRole").value.toLowerCase();

  if (!validarCamposRegistro(nombre, email, pass)) return;

  const nuevoUsuario = UsuarioFactory.crearUsuario(nombre, email, pass, rol);
  usuarios.push(nuevoUsuario);
  guardarUsuarios();
  Swal.fire("Registro exitoso", "Tu cuenta ha sido creada.", "success");
};

AuthController.login = function () {
  const email = document.getElementById("loginEmail").value;
  const pass = document.getElementById("loginPassword").value;
  if (!validarCamposLogin(email, pass)) return;
  const user = usuarios.find(u => u.email === email && u.pass === pass);
  if (user) {
    localStorage.setItem("actual", JSON.stringify(user));
    window.location.href = "dashboard.html";
  } else {
    Swal.fire("Error", "Credenciales inválidas", "error");
  }
};

SolicitudController.crearSolicitud = function () {
  const fecha = document.getElementById("fecha").value;
  const tipo = document.getElementById("tipoResiduo").value;
  const peso = parseFloat(document.getElementById("peso").value);

  if (!validarSolicitud(fecha, peso)) return;

  const usuario = JSON.parse(localStorage.getItem("actual"));

  let strategy;
  if (tipo === "reciclable") strategy = new ReciclableStrategy();
  else if (tipo === "peligroso") strategy = new PeligrosoStrategy();

  const puntosGanados = strategy.calcular(peso);
  usuario.puntos += puntosGanados;
  localStorage.setItem("actual", JSON.stringify(usuario));

  const index = usuarios.findIndex(u => u.email === usuario.email);
  usuarios[index] = usuario;
  guardarUsuarios();

  solicitudes.push({ usuario: usuario.nombre, fecha, tipo, peso, estado: "pendiente" });
  guardarSolicitudes();

  Notificador.enviar(`Nueva solicitud de ${usuario.nombre} para ${tipo} (${peso}kg)`);

  Swal.fire("¡Solicitud enviada!", "Se notificará vía WhatsApp (simulado).", "success");

  DashboardController.mostrar();
};

