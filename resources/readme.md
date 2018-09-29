# CLEANUP

# Utilities - 'functions/' & 'mixins/'
Contain Reusable CSS Helpers To Simplify Various Actions Within The
'Design System'

# Tokens - 'tokens/'
Each Token File Contains The Functions, Mixins And Variables Directly Associated
With Each Token.

When The Primary Goal Of A Utility Is To Abstract The Token From A Module Place
It Within The Token File. Otherwise Throw The Utility Into It's Folder
( 'functions/' or 'mixins/' ).

## Token Containers - 'tokens/containers'
Container Tokens Act As A Configuration Store Of All Tokens. This Was Done To
* Abstract Usage Throughout 'Design System'
* Avoid Conflict With Global Namespace/Variables
* Enable The Creation/Use Of A Frontend/GUI For Designers To Alter/Save Token
Configuration As JSON And Convert To '$token' Sass Map
* ^ This Will Make Modification By Designers Simple-ish And If Done Correctly
Opens The Possibility Of Making Customizable Interfaces That Clients/Users Can
Edit -> Compile

## Design Tokens - 'tokens/design'
Each Design Token Contain The Base Set of Tokens Used Throughout The System.
Ex. Colors, Spacing, Breakpoints, etc.

## Module Tokens - 'tokens/modules'
Includes Configurable Options For Most Modules.
* Main Goal With Module Tokens Was To Include Settings That Would Be Most Likely
To Change Across Projects. Main Example Being Spacing Between Elements etc.


# Class Naming Convention

## Global Naming Convention:
Reusable Elements Should Be Broken Down Into Components, Modules, Objects
Whenever Possible.

## Modifiers:
Double Hyphen Between Element And Modifier. States Are NOT Modifiers.

Modifiers Can Be Assigned To Parent Elements To Simplify Theme-ing Entire
Block Of Elements. For Example:

.parent--purple .child {
    color: purple;
}

## Children:
Single Hyphen Between Parent And Child.

Modifiers Are Created When It Makes Sense, Nested Styles Can Be Used For
Minor Changes For Larger Blocks/Elements.

## States:
Do Not Need Prefixes     .active, .hover, .locked

## Functions/Mixins:
Single Hyphen Between Words


# Minified SVG Includes

Run All SVG's Within This Directory Through A Minifier Like 'SVGO' Before Adding
To Library.

*Don't Forget To Keep ViewBox On SVG's So They Can Be Resized With CSS*

* Icons Within This Directory Are Used Within HTML
* Icons Within '/web/' Directory Are Typically Used Within 'img' Tags
