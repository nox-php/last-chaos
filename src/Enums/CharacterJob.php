<?php

namespace Nox\LastChaos\Enums;

use Nox\LastChaos\Models\Character;

enum CharacterJob: string
{
    // Titan
    case Warmaster = '0.1';
    case Highlander = '0.2';

    // Knight
    case RoyalKnight = '1.1';
    case Templar = '1.2';

    // Healer
    case Archer = '2.1';
    case Cleric = '2.2';

    // Mage
    case Wizard = '3.1';
    case Witch = '3.2';

    // Rogue
    case Ranger = '4.1';
    case Assassin = '4.2';

    // Sorcerer
    case Specialist = '5.1';
    case Elementalist = '5.2';

    // Ex-Rogue
    case ExRanger = '7.1';
    case ExAssassin = '7.2';

    // ArchMage
    case ArchWizard = '8.1';
    case ArchWitch = '8.2';

    public static function character(Character $character): ?CharacterJob
    {
        return self::tryFrom($character->a_job . '.' . $character->a_job2);
    }
}