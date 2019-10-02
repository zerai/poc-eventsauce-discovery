# poc EventSauce discovery

discovery/experimenting/testing della nuova libreria per CQRS/ES.

Possibile Dominio per demo:
- Todo App - (stesso dominio prooph demo)
- Food Delivery - (?? cercare modello justIT) 
- Simil Uber - (vedi php serviceBus)
- Simil Amazon (vedi mauro servienti repository)

Dev Note:
- attualmente la libreria non fornisce un'integrazione ufficiale con symfony.
- possibile integrazione con CompostDDD/messaging lib.
- warning - [issue](https://github.com/EventSaucePHP/EventSauce/issues/58#event-2643267344) valutare implicazioni... 


## Common Task
- [x] - Create symfony project

- [x] - Enrich dev enviroment (phpunit phpstan cs-fixer fpp behat)

- [x] - Create CI pipeline (define common steps)

- [x] - Bring in Eventsauce

- [x] - Bring in CompostDDD (messaging)

- [ ] - Bring in ServiceBus with abstraction (Tactician, Messenger, or EventSauce built-in serviceBus ?)

- [x] - Setup a docker dev enviroment


##  Branch based task


### TodoApplication

- [x] - Create new branch and psr namespace

- [ ] - Build a basic user model
      
- [ ] - Build a basic task model

- [ ] - HowTo Projection? extract code for reuse, maybe separate lib or bundle