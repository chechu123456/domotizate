var version = 'v27';

// Instalación
self.addEventListener('install', function(event){
    //Si no se llama al waitUntil no va pasar de estado el ServiceWorker
    event.waitUntil(
        //Abrir el cache Storage con la clave concreta, esto me devuelve una promesa
        caches.open(version)
        //La promesa me devuelve el objeto cache al que puedo añadir los recursos
        .then(function(cache){
            return cache.addAll([
                'css/estilos.css',
                'panel/index.php',
                'panel/graficas.php',
                'panel/cambiarTema.php',
                'panel/configuracion.php',
                'panel/logCasa.php',
                'panel/cogerDatosLog.php',
                'index.php'
            ]);
        })
    );
});

// Activación
//En el proceso de activación del service worker
//Si se actualiza, borramos caches antiguas 

self.addEventListener('activate', event => {
    event.waitUntil(
      caches.keys().then(cacheNames => Promise.all(
        cacheNames
          .filter(cacheName => cacheName !== version)
          .map(cacheName => caches.delete(cacheName))
      ))
    );
   });

//Activacion
// Primero lee la cache
//Capturar las peticiones y responder con los datos que tenemos en la cache
//cuando esté offline
/*
self.addEventListener('fetch', function(event){
    //Escuchamos el evento fetch que nos devolverá un respond
    event.respondWith(
        //Si la petición de ese recurso existe y si existe en nuestra caché
        caches.match(event.request)
        .then(function(response){
            //Si la cache nos da una respuesta la devolvemos
            if(response){
                return response;
            }

            //Si no hay Conexión que le devuelva una respuesta complementaria
            if(!navigator.onLine){
                return caches.match(new Request('panel/index.php'))
            }

            //Si no lo tenemos cacheado se lo pedimos a la red si hay conexión
            return fetch(event.request);
        })
    )
});
*/

//Primero se conecta a Internet y compara con lo que tiene en la cache
//Si es distinto descargalo nuevo
self.addEventListener('fetch', function(event) {
    event.respondWith(
      caches.open(version).then(function(cache) {
        return cache.match(event.request).then(function (response) {
          return response || fetch(event.request).then(function(response) {
            cache.put(event.request, response.clone());
            return response;
          });
        });
      })
    );
  });