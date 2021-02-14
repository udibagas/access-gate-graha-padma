import exportFromJson from 'export-from-json';

export default {
  created() {
    this.fetchData();
  },

  data() {
    return {
      tableData: [],
      pageSizes: [15, 25, 50, 100],
      pagination: {
        current_page: 1,
        from: 0,
        to: 0,
        total: 0,
        per_page: 15
      },
      filters: {},
      sortColumn: 'id',
      sortDirection: 'asc',
      keyword: '',
      paginated: false,
      showForm: false,
      form: {},
      errors: {},
      loading: false
    }
  },

  methods: {
    fetchData() {
      const params = {
        page: this.pagination.current_page,
        keyword: this.keyword,
        sortColumn: this.sortColumn,
        sortDirection: this.sortDirection,
        paginated: this.paginated,
        ...this.filters,
        ...this.pagination
      }

      this.loading = true;

      this.$axios.$get(this.url, { params }).then(r => {
        if (this.paginated) {
          this.tableData = r.data;
          const { from, to, total, current_page, per_page } = r;
          this.pagination = { from, to, total, current_page, per_page };
        } else {
          this.tableData = r;
        }
      }).catch(e => {
        this.$message({ message: e.response.data.message, type: 'error', showClose: true });
      }).finally(() => this.loading = false);
    },

    exportData() {
      const params = {
        keyword: this.keyword,
        sortColumn: this.sortColumn,
        sortDirection: this.sortDirection,
        paginated: false,
        action: 'export',
        ...this.filters
      }

      this.loading = true;

      this.$axios.$get(`${this.url}/export`, { params }).then(r => {
        exportFromJson({
						data: r.data,
						fileName: r.filename,
						exportType: "xls"
					});
      }).catch(e => {
        this.$message({ message: e.response.data.message, type: 'error', showClose: true });
      }).finally(() => this.loading = false);
    },

    deleteData(id) {
      this.$confirm('Konfirmasi', 'Anda yakin?', { type: 'warning' }).then(() => {
        this.$axios.$delete(`${this.url}/${id}`).then(r => {
          this.$message({ message: r.message, type: 'success', showClose: true });
          this.fetchData();
        }).catch(e => {
          this.$message({ message: e.response.data.message, type: 'error', showClose: true });
        });
      }).catch(e => console.log(e));
    },

    openForm(data) {
      this.form = JSON.parse(JSON.stringify(data));
      this.showForm = true;
    },

    save() {
      this.loading = true;

      this.$axios({
        method: this.form.id ? 'PUT' : 'POST',
        url: this.form.id ? `${this.url}/${this.form.id}` : this.url,
        data: this.form
      }).then(r => {
        this.$message({ message: r.data.message, type: 'success', showClose: true });
        this.closeForm();
        this.fetchData();
      }).catch(e => {
        this.$message({ message: e.response.data.message, type: 'error', showClose: true });

        if (e.response.status == 422) {
          this.errors = e.response.data.errors;
        }
      }).finally(() => this.loading = false);
    },

    closeForm() {
      this.showForm = false;
      this.form = {};
      this.errors = {};
    },

    refreshData() {
      this.pagination.current_page = 1;
      this.keyword = '';
      this.fetchData();
    },

    sortChange(c) {
      if (c.prop != this.sort || c.order != this.order) {
        this.sortColumn = c.prop;
        this.sortDirection = c.order == 'ascending' ? 'asc' : 'desc';
        this.pagination.current_page = 1;
				this.fetchData();
			}
    },

    filterChange(filter) {
      this.filters = {...this.filters, ...filter}
      this.pagination.current_page = 1
      this.fetchData()
    },

    currentChange(page) {
      this.pagination.current_page = page
			this.fetchData()
    },

    sizeChange(size) {
      this.pagination.per_page = size
			this.pagination.current_page = 1
			this.fetchData()
    },
  }
}
