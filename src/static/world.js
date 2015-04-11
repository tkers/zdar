var ACTIONS = {
  goto : function (next, text) {
    return function () {
      if (text) output(text);
      AREA = WORLD.areas[next];
      output(AREA.description);
    }
  },
  take : function (item, text) {
    return function () {
      if (text) output(text);
      INVENTORY[item] = true;
    }
  },
  has : function (item, yes, no) {
    return function () {
      if (INVENTORY[item])
        return yes;
      else
        return no;
    }
  }
};

var WORLD = {
  intro : "You are Captain Bob, self-employed astronaut.\n\nIn one of your many daring missions, your space craft is intercepted by an unknown starship. Seeing no heroic way out of this situation whatsoever, you rush towards the escape pods. You manage to get away safely, only seconds before your beloved ship is completely torn apart by the starship\'s ion cannons.\n\nAs your escape pod automatically plots a course to the nearest planet, you are able to distinguish a yellow, triangle-shaped symbol on the hull of the hostile starship.\n\nA few hours later, with the starship far out of sight, you crash onto the surface of a planet unknown to you. The airlock opens with a hiss, and you step outside...",
  start : "crash-site",
  areas : {
    "crash-site" : {
      name : "Crash Site",
      description : "Your escape pod is still lying in the crater it created on impact. It looks badly damaged (both the pod and the surface).",
      commands : {
        look : "You take a look around. There seems to be nothing but dust on this planet, but you are able to make out 2 paths leading to the north and the east...",
        enter : ACTIONS.goto("pod", "You enter your escape pod.")
      },
      exits : {

      }
    },
    "pod" : {
      name : "Escape Pod",
      description : "Flashing lights near the control panel indicate that the pod was badly damaged in the crash. Finn is swimming around in his fishbowl, not looking too concerned about the situation.",
      commands : {
        exit : ACTIONS.goto("crash-site", "You step trough the airlock, and are back outside again."),
        "take finn" : ACTIONS.take("finn", "You take Finn with you."),
        "take fish" : ACTIONS.take("finn", "You take Finn with you.")
      }
    }
  }
}

var INVENTORY = {};
var AREA = WORLD.areas[WORLD.start];
