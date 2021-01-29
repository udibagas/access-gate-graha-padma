<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">ACCESS LOG</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-button
						@click="exportData"
						type="primary"
						icon="el-icon-download"
						size="small"
						title="Download Log"
						plain
					></el-button>
				</el-form-item>

				<el-form-item class="mb-0">
					<el-date-picker
						style="width: 250px"
						size="small"
						start-placeholder="Start"
						end-placeholder="End"
						type="daterange"
						format="dd-MMM-yyyy"
						value-format="yyyy-MM-dd"
						v-model="filters.dateRange"
						@change="fetchData"
					></el-date-picker>
				</el-form-item>

				<el-form-item class="mb-0">
					<el-input
						v-model="keyword"
						placeholder="Cari"
						prefix-icon="el-icon-search"
						clearable
						size="small"
						@change="
							() => {
								this.pagination.current_page = 1
								fetchData()
							}
						"
					></el-input>
				</el-form-item>
			</el-form>
		</div>

		<el-table
			height="calc(100vh - 197px)"
			stripe
			v-loading="loading"
			:data="tableData"
			@sort-change="sortChange"
			@filter-change="filterChange"
			:default-sort="{ prop: 'created_at', order: 'descending' }"
		>
			<el-table-column
				type="index"
				label="#"
				:index="paginated ? pagination.from : 1"
			></el-table-column>

			<el-table-column prop="time" label="Waktu" width="200"></el-table-column>

			<el-table-column
				prop="access_gate.name"
				label="Gate"
				align="center"
				header-align="center"
				column-key="access_gate_id"
				width="120"
				:filters="filterGate"
			></el-table-column>

			<el-table-column
				prop="access_gate.type"
				label="Jenis"
				align="center"
				header-align="center"
				column-key="type"
				width="120"
				:filters="[
					{ text: 'IN', value: 'IN' },
					{ text: 'OUT', value: 'OUT' },
				]"
			></el-table-column>

			<el-table-column prop="member.name" label="Nama"></el-table-column>

			<el-table-column
				prop="member.card_number"
				label="Nomor Kartu"
			></el-table-column>

			<el-table-column align="center" header-align="center" width="60">
				<template slot="header">
					<el-button
						type="text"
						icon="el-icon-refresh"
						@click="refreshData"
					></el-button>
				</template>
			</el-table-column>
		</el-table>

		<Pagination
			@current-change="currentChange"
			@size-change="sizeChange"
			:page-sizes="pageSizes"
			:pagination="pagination"
			:paginated="paginated"
			:total="tableData.length"
		/>
	</el-card>
</template>

<script>
import crud from '../mixins/crud';

export default {
  mixins: [crud],
  data() {
    return {
      url: '/api/accessLogs',
      paginated: true,
      filterGate: [],
    }
  },

  created() {
    const params = { paginated: false };
    this.$axios.$get('/api/accessGate', { params }).then(r => {
      this.filterGate = r.map(g => {
        return { value: g.id, text: g.name }
      });
    })
  }
}
</script>
