let Name = document.getElementById("name");
let nationcode = document.getElementById("nationcode");
let stunumber = document.getElementById("stunumber");
let tell = document.getElementById("tell");
let inputs = document.getElementsByTagName("input");
let price = document.getElementById("price");
let form = document.getElementsByTagName("form");

let Namein = document.getElementById("namein");
let nationcodein = document.getElementById("nationcodein");
let stunumberin = document.getElementById("stunumberin");
let tellin = document.getElementById("tellin");

const showNameError = () => {
  Name.style.display = "block";
};

stunumberin.addEventListener("keyup", () => {
  if (stunumberin.value.length == 10) {
    stunumber.style.display = "none";
    const reg10 = new RegExp("^[9][0-9][1][2][3][5][8][0-9][0-9][0-9]");
    if (reg10.test(stunumberin.value)) {
      price.innerHTML = "رایگان";
    }
  } else if (stunumberin.value.length == 11) {
    stunumber.style.display = "none";
    const reg11 = new RegExp("^[4][0][0-1][1][2][3][5][8][0-9][0-9][0-9]");
    if (reg11.test(stunumberin.value)) {
      price.innerHTML = "رایگان";
    }
  } else {
    stunumber.style.display = "block";
    Name.style.display = "none";
    nationcode.style.display = "none";
    tell.style.display = "none";
    price.innerHTML = "20,000 تومان";
  }
});
Namein.addEventListener("keyup", () => {
  Namein.value.length < 4
    ? (Name.style.display = "block") &&
      (nationcode.style.display = "none") &&
      (tell.style.display = "none") &&
      stunumber.style.display == "none"
    : (Name.style.display = "none");
});
nationcodein.addEventListener("keyup", () => {
  nationcodein.value.length < 10
    ? (Name.style.display = "none") &&
      (nationcode.style.display = "block") &&
      (tell.style.display = "none") &&
      stunumber.style.display == "none"
    : (nationcode.style.display = "none");
});
tellin.addEventListener("keyup", () => {
  let regex = new RegExp("^(\\+98|0)?9\\d{9}$");
  regex.test(tellin.value)
    ? (tell.style.display = "none")
    : (Name.style.display = "none") &&
      (nationcode.style.display = "none") &&
      (tell.style.display = "block") &&
      stunumber.style.display == "none";
});
form[0].addEventListener("submit", () => {
  if (
    Name.style.display == "block" ||
    nationcode.style.display == "block" ||
    tell.style.display == "block" 
  ) {
    return false;
  }
  else{
  }
});
