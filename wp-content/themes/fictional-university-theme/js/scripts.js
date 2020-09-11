import "../css/style.css"

// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
import HeroSlider from "./modules/HeroSlider"
import GoogleMap from "./modules/GoogleMap"
import Search from "./modules/Search"
import MyNotes from "./modules/MyNotes"
import Like from "./modules/Like"
// import Search from "./modules/jquery-free/Search-Without-jQuery"
// import MyNotes from "./modules/jquery-free/MyNotes-Without-jQuery"
// import Like from "./modules/jquery-free/Like-Without-jQuery"


// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
const googleMap = new GoogleMap()
const search = new Search();
const myNotes = new MyNotes();
const like = new Like();

// Allow new JS and CSS to load in browser without a traditional page refresh
if (module.hot) {
  module.hot.accept()
}
