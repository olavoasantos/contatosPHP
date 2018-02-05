<template>
  <div :class='avatarClass'> {{ name | firstChar }} </div>
</template>

<script>
  export default {

    props: {
      /**
       * String contendo o nome do contato
       * para apresentação no avatar.
       */
      name: { type: String }
    },
    
    filters: {
      /**
       * firstChar
       * ---
       * Filtro que retorna a primeira letra de uma string.
       * @param   {String}  value
       * @returns {String}  Primeiro caractere da string
       */
      firstChar(value) {
        if (!value) return '';

        return value.charAt(0);
      }
    },
    
    computed: {
      /**
       * avatarClass
       * ---
       * Constrói o conjunto de classes do avatar, com uma
       * cor de fundo aleatória.
       */
      avatarClass() {
        let color = [
          'grey', 'red', 'orange', 'yellow-dark', 'green', 'teal',
          'blue', 'indigo', 'purple', 'pink'
        ][ Math.floor(Math.random() * 10) + 0  ];

        return `flex-center font-bold text-white rounded-full h-8 w-8 bg-${color} p-2 select-none`;
      }
      
    }
  }
</script>
