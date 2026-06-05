<?php
it('only logged users can access the subscribers', function(){
     getJson(route('subscribers.index', $this->emailList))
        ->assertUnauthorized();
});