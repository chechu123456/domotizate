var version = 'v21';

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
// REVISAR FUNCIONAMIENTO
self.addEventListener("activate", function(event){
    event.waitUntil(
        caches.keys()
        .then(function(keys){
            return Promise.all(keys.filter(function(key){
                return key !==version;
            }).map(function(key){
                return caches.delete(key);
            }))
        })
    )
});


//Activacion
//Capturar las peticiones y responder con los datos que tenemos en la cache
//cuando esté offline
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