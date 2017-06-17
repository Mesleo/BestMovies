<h1>Películas</h1>

<p>Pequeño programa para guardar información sobre tus películas, es decir, como si fuera tu videoteca personalizada.
Creada con Symfony haciendo uso de Bootstrap, jQuery, Ajax, etc.<p>

<p>Para poder añadir/editar películas y puntuarlas deberás crearte un usuario con permisos de administrador mediante la consola de comandos: http://symfony.com/doc/current/bundles/FOSUserBundle/command_line_tools.html.</p>

<p>Aquí podrás guardar información sobre cada una de tus películas (actores, director, título, tamaño de archivo, url del tráiler, etc) así como la ubicación donde las tienes alojadas (discos duros, DVDs, etc).</p>

<p>Por otra parte, cualquier usuario podrá ver la información de tus películas sin tener acceso a ningún tipo de edición ni insercción. Los usuarios podrán hacer lo siguiente:</p>

<ul>
  <li>Buscar películas por:
      <ul>
          <li>Título</li>
          <li>Actores</li>
          <li>Director</li>
          <li>Saga</li>
          <li>País</li>
          <li>Todo lo anterior</li> 
      </ul>
  </li>
  <br>
  <li>Ordenar los resultados por:
      <ul>
          <li>Novedad</li>
          <li>Puntuación (de la web)</li>
          <li>Alfabéticamente</li>
          <li>Duración</li>
          <li>País</li>
          <li>Saga</li>
          <li>Fecha añadido</li>
          <li>Director</li>
          <li>Tamaño archivo</li>
      </ul>
      <p>El orden se puede invertir</p>
  </li>
  <br>
  <li>Filtrar resultados por:
      <ul>
          <li>Año</li>
          <li>Duración (minutos)</li>
          <li>Categoría (se pueden elegir varias)</li>
          <li>Vistas/No vistas (Sólo para el usuario administrador)</li>
      </ul>
  </li>
  <br>
  <li>Valorar películas:
      <ul>
          <li>Puntuación (del 1 al 10)</li>
          <li>Poner un título al comentario</li>
          <li>Una descripción de tu comentario</li>
          <li>Eliminar comentario o editar cuando quiera (sólo se permite un comentario por película para cada usuario.</li>
      </ul>
  </li>
</ul>
<p>Se mostrarán las películas que se desee en la configuración del servidor para obtener los resultados de forma paginada.</p>
