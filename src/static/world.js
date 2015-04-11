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
      if (INVENTORY[item]) {
        if (yes instanceof Function) {
          yes();
        }
        else {
          output(yes);
        }
      }
      else {
        if (no instanceof Function) {
          no();
        }
        else {
          output(no);
        }
      }
    }
  },
  end : function (text) {
    return function () {
      output(text);
      gameover();
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
        enter : ACTIONS.goto("pod", "You enter your escape pod."),
        "shoot" : ACTIONS.has("blaster", ACTIONS.take("destroyed-rock", "You skillfully aim your blaster at the rock, and pull the trigger. The rock explodes, allowing you to pass."), "You don't have anything to shoot with."),
        "shoot rock" : ACTIONS.has("blaster", ACTIONS.take("destroyed-rock", "You skillfully aim your blaster at the rock, and pull the trigger. The rock explodes, allowing you to pass."), "You don't have anything to shoot with.")
      },
      exits : {
        north : ACTIONS.has("destroyed-rock", ACTIONS.goto("clearing"), "The road to the north is blocked by a rock."),
        east : ACTIONS.goto("dump")
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
    },
    "dump" : {
      name : "Garbage Dump",
      description : "You arrive at a place which appears to be a garbage dump. There could be some usefull tools lying around.",
      commands : {
        search : ACTIONS.has("blaster", "You search for anything usefull, but don't find anything.", ACTIONS.take("blaster", "Between piles of garbage, you find an old blaster."))
      },
      exits : {
        west : ACTIONS.goto("crash-site")
      }
    },
    "clearing" : {
      name : "Outside Spaceship",
      description : "You found a small, dusty and forgotten spaceship. It looks capable enough to get you off this planet.",
      exits : {
        south : ACTIONS.goto("crash-site")
      },
      commands : {
        enter : ACTIONS.goto("spaceship", "You enter the spaceship. The airlock closes behind you.")
      }
    },
    "spaceship" : {
      name : "Inside Spaceship",
      description : "With a quick glance at the control panel, you can see that the spaceship is able to take off without any problems.",
      commands : {
        exit : "The airlock won't open anymore.",
        launch : ACTIONS.has("finn", ACTIONS.goto("space", "You fasten your seatbelt and press the ignition button. The engines rumble, your seat is shaking, but soon enough, you lift off.\n\nFinn is swimming in his bowl, still uninterested in anything that is happening. As you move away from the dusty planet, you give him some food, to celebrate your survival."), ACTIONS.goto("space", "You fasten your seatbelt and press the ignition button. The engines rumble, your seat is shaking, but soon enough, you lift off."))
      }
    },
    "space" : {
      name : "Outer Space",
      description : "Around you is nothing but vast, empty space.",
      commands : {
        "open airlock" : ACTIONS.end("You open the airlock..."),
        exit : ACTIONS.end("You open the airlock...")
      }
    }
  },
  commands : {
    "pat finn" : ACTIONS.has("finn", "^_^", "You scratch your head."),
    "ask finn" : ACTIONS.has("finn", "You ask Finn for help, but your trusty goldfish isn't able to help you, even if he could understand you.", "You scratch your head."),
    "promote finn" : ACTIONS.has("finn", "You promote your trusty goldfish to admiral. Finn gives you a questioning look.", "You scratch your head.")
  }
}

var INVENTORY = {};
var AREA = WORLD.areas[WORLD.start];
