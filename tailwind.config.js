/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    safelist: [
      "bg-yellow-100",
      "text-yellow-800",
      "bg-green-100",
      "text-green-800",
      "bg-blue-100",
      "text-blue-800",
      "bg-red-100",
      "text-red-800",
      "bg-gray-100",
      "text-gray-800",
      "bg-amber-100",
      "text-amber-800",
      "bg-violet-100",
      "text-violet-800",
      "bg-rose-100",
      "text-rose-800",
    ],
    theme: {
      extend: {},
    },
    plugins: [],
  }
