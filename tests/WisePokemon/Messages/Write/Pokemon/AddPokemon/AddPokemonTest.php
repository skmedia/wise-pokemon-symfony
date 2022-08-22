<?php

declare(strict_types=1);

namespace App\Tests\WisePokemon\Messages\Write\Pokemon\AddPokemon;

use App\Tests\DatabaseTestCase;
use App\WisePokemon\Domain\Pokemon\Pokemon;
use App\WisePokemon\Domain\Pokemon\PokemonId;
use App\WisePokemon\Domain\Pokemon\PokemonRepository;
use App\WisePokemon\Messages\Write\Pokemon\AddPokemon\AddPokemon;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class AddPokemonTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function it_should_add_a_pokemon()
    {
        $this->dispatchWriteMessage(new AddPokemon(1, 'name', ['grass', 'poison']));

        $repo = $this->getContainer()->get(PokemonRepository::class);
        $pokemon = $repo->findOneById(PokemonId::withPrefix('1'));

        $this->assertInstanceOf(Pokemon::class, $pokemon);
    }

    /**
     * @test
     */
    public function it_should_throw_an_error_when_the_name_is_missing()
    {
        $this->expectException(ValidationFailedException::class);

        $this->dispatchWriteMessage(new AddPokemon(1, '', ['grass', 'poison']));
    }
}
