# wwes-spudbot-setups
Setups used for SpeedGameBot on werewolv.es

Setups are defined in the json files within this repo. The bot will attempt to load any .json file within this repo, for convenience these are currently split out into folders denoting how many players the setups handle, though this isn't required and any folder structure will work.

A valid setup file looks something like:

```json
[
    {
        "roles": [
            "Harlot",
            ["Villager", "Stalker"],
            ["Villager", "Tanner"],
            "Seer",
            ["Villager", "Villager", "Villager", "Villager", "MapleWolf"],
            "Shapeshifter"
        ],
        "items": [
            [["Crossbow", "ShaneProofCrossbow"], "WolfPelt"],
            ["SteelArmour", null]
        ],
        "auras": [
            ["Crippled", "Maimed", "Concussed"],
            ["Inebriated", null]
        ],
        "bad": ["3219b9c4"],
        "disabled": false,
        "time": ["VariableSpud"],
        "roleAnnouncement": ["Full"],
        "itemAnnouncement": ["Full"],
        "itemDistribution": ["Random"],
        "auraAnnouncement": ["Full"],
        "auraDistribution": ["Random"]
    }
]
```

The important bit is the `roles` array which defines which roles to attempt to put into the game.

If a role is defined as just the string (eg `"Harlot"`) then it will put a Harlot in the game.

If a role is defined as an array (eg `["Villager", "Villager", "Villager", "Villager", "MapleWolf"]`) then it will randomly select an entry from this list to add to the game. At the moment there is no way to say "put a MapleWolf in if there isn't one already" but this is something I hope to support in future.

It's also possible to define roles that are holding items, eg:
```json
{"role": "Seer", "items": ["SteelArmour"]},
```

And the `items` dict is randomised the same way as roles are described above (eg `["SteelArmour", "WolfPelt"]` would allocate both, but `[["SteelArmour", "WolfPelt"]]` would allocate one or the other. Items can also be "null" to allow randomising no item.)

```json
{"role": "Seer", "items": [ "Crossbow", ["SteelArmour","WolfPelt", null]]},
```
Would be a seer with a Crossbow and  1 of "SteelArmour", "WolfPelt" or "No Armour".

You can also give roles auras, eg:
```json
{"role": "Seer", "auras": ["Inebriated"]},
```

A role can also be quite complex:
```json
[{"role": "EssenceThief", "items": ["PlotArmour"]}, {"role": "Tanner", "items": [["SteelArmour", "WolfPelt"]]}],
```
Which would end up being either EssenceThief+PlotArmour, Tanner+SteelArmour, or Tanner+WolfPelt.


The bot looks for setup definitions with the same number of defined roles as players (so it won't try to add villagers to a 5-player setup for a 6-player game.)


The setup can also be told to use a specific time setting (One of: `"VariableSpud"`, `"FourOne"`, `"EightTwo"`, `"TenFive"`) or an array of times (`["VariableSpud", "FourOne"]`) to make it pick a random one.

The setup can also be told to use a specific roleAnnouncement setting (One of: `"Full"`, `"FactionCounts"`, `"Factions"`, `"None"`) or an array of settings (`["Full", "Full", "None"]`) to make it pick a random one.

The setup can also be told to use a specific itemAnnouncement setting (One of: `"Full"`, `"None"`) or an array of settings (`["Full", "Full", "None"]`) to make it pick a random one.

The setup can also be told to use a specific itemDistribution setting (One of: `"Random"`, `"Even"`) or an array of settings (`["Random", "Even", "Even"]`) to make it pick a random one.

The setup can also be told to use a specific auraAnnouncement setting (One of: `"Full"`, `"None"`) or an array of settings (`["Full", "Full", "None"]`) to make it pick a random one.

The setup can also be told to use a specific auraDistribution setting (One of: `"Random"`, `"Even"`) or an array of settings (`["Random", "Even", "Even"]`) to make it pick a random one.

Themes can't be set for a setup, the bot will always randomise the theme from a list of themes it has access to.


Setups can be marked with `"disabled": true,` to make the bot not attempt to use them, or with `"bad": ["3219b9c4"],` to make it not use specific variants of a setup. The variant ID doesn't really mean anything, and is a hash representation of the roles that ended up in the game (`base_convert(crc32(json_encode($setup['roles'])), 10, 16);` for anyone that cares). If a setup seems bad raise an issue and I'll figure out the correct variant ID (The bot logs the variant id in it's log file when it creates the game).


Unlike normal json files, the setups json file can contain comments. in the form of `/* comment */`


Pull-Requests or Issues with suggested setups are welcome. It's safe to assume the bot has access to all the roles needed to make any required setup.
