const words = [
  "Pendataan Kendaraan Terintegrasi",
  "Parkir Aman & Tertib"
]

let i = 0
let j = 0
let isDeleting = false
const target = document.getElementById("typing-text")

function type() {
  const word = words[i]

  if (!isDeleting) {
    target.textContent = word.slice(0, j++)
    if (j > word.length) {
      isDeleting = true
      setTimeout(type, 1500)
      return
    }
  } else {
    target.textContent = word.slice(0, j--)
    if (j === 0) {
      isDeleting = false
      i = (i + 1) % words.length
    }
  }
  setTimeout(type, isDeleting ? 50 : 100)
}

type()