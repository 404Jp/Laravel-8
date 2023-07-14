crear base de datos  "crud_roles_stisla" o cambiar nombre en archivo .env
ejecutar php artisan migrate
crear permisos ejecutar php artisan db:seed --class=SeederTablaPermisos
En entorno local levantar php artisan serve
En servidor configurar .env  para la correcta funciones con la BD
Al crear el perfil admin habilitar desde la base de datos tabla user coolumna activo con valor 1. 

Configuraciones iniciales de los roles:
--------------------------------------

Lider : habilitar permisos [lider,ver-grupos]. *lider permisos minimos para sus grupos.
Usuario : habilitar solamente [ver-grupos]. *usuario con minimo privilegio.
Supervisor de grupos: habilitar permisos[editar-grupos,ver-grupos,crear-grupo,borrar-grupo]. *supervisor para todos los grupos.

Para crear un rol nuevo dar permisos correspondientes desde seccion roles.

instrucciones para el manejo de lideres en la seccion usuarios 




