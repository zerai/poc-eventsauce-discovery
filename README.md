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

- [ ] - Enrich dev enviroment (phpunit phpstan cs-fixer fpp behat)

- [ ] - Create CI pipeline (define common steps)

- [ ] - Bring in Eventsauce

- [ ] - Bring in CompostDDD (messaging)

- [ ] - Bring in ServiceBus with abstraction (Tactician, Messenger, or EventSauce built-in serviceBus ?)


##  Branch based task


### TodoApplication

- [ ] - Create new branch and psr namespace

- [ ] - Build a basic user model
      
- [ ] - Build a basic task model