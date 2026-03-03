module.exports = {
  content: [
    "./*.php",
    "./admin/**/*.php",
    "./includes/**/*.php"
  ],
  theme: {
    extend: {
      animation: {
        float: "float 6s ease-in-out infinite",
        blink: "blink 1s step-start infinite",
      },
      keyframes: {
        float: {
          "0%, 100%": { transform: "translateY(0)" },
          "50%": { transform: "translateY(-10px)" },
        },
        blink: {
          "50%": { opacity: "0" },
        },
      },
    },
  },
  plugins: [],
}