export default {
  data() {
    return {
      userList: [],
      memberList: [],
      accessGateList: [],
      cameraList: [],
    }
  },

  computed: {
    accessGateFilter() {
      return this.accessGateList.map(u => {
        return {text: u.name, value: u.id}
      })
    },
  },

  methods: {

    getList(url, listContainer, keyword = '') {
      const params = {
        keyword,
        paginated: true,
        per_page: 10
      }

      this.$axios.$get(url, { params }).then(r => {
        this[listContainer] = r.data
      }).catch(e => {
        this.$message({
          message: e.response.data.message,
          type: 'error'
        })
      })
    }

  }
}
