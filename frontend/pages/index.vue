<template>
	<el-card :body-style="{ padding: '0px' }">
		<div slot="header" class="d-flex justify-content-between">
			<h3 class="text-muted mt-2">ACCESS LOG</h3>
			<el-form inline @submit.native.prevent>
				<el-form-item class="mb-0">
					<el-dropdown type="primary">
						<el-button type="primary" size="mini" icon="el-icon-s-tools">
							AKSI <i class="el-icon-arrow-down el-icon--right"></i>
						</el-button>
						<el-dropdown-menu slot="dropdown">
							<el-dropdown-item
								@click.native.prevent="exportData"
								icon="el-icon-download"
								>Download Log</el-dropdown-item
							>
							<!-- TODO -->
							<!-- <el-dropdown-item
								@click.native.prevent="exportData"
								icon="el-icon-printer"
								>Print Log</el-dropdown-item
							> -->
							<el-dropdown-item
								@click.native.prevent="deleteLog"
								icon="el-icon-delete"
							>
								Hapus Log
							</el-dropdown-item>
						</el-dropdown-menu>
					</el-dropdown>
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
			height="calc(100vh - 171px)"
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

			<el-table-column
				prop="member.plate_number"
				label="Plat Nomor"
			></el-table-column>

			<el-table-column align="center" header-align="center" width="80">
				<template slot="header">
					<el-button
						type="primary"
						icon="el-icon-refresh"
						@click="refreshData"
						plain
						size="mini"
					></el-button>
				</template>

				<template slot-scope="scope">
					<el-button
						icon="el-icon-camera"
						plain
						type="primary"
						size="mini"
						@click.native.prevent="showSnapshot(scope.row)"
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

		<el-dialog
			center
			v-if="selectedData.id"
			:visible.sync="snapshotDialog"
			width="1200px"
			title="SNAPSHOT"
		>
			<div class="bg-secondary p-3 mb-3 text-white">
				<span class="label">Nama</span> : {{ selectedData.member.name }} <br />
				<span class="label">Nomor Kartu</span> :
				{{ selectedData.member.card_number }} <br />
				<span class="label">Plat Nomor</span> :
				{{ selectedData.member.plate_number }} <br />
				<span class="label">Gate</span> :
				{{ selectedData.access_gate.name }} ({{
					selectedData.access_gate.type
				}})
				<br />
				<span class="label">Waktu</span> : {{ selectedData.time }}
			</div>

			<div class="d-flex justify-content-between">
				<div v-for="snapshot in selectedData.snapshots" :key="snapshot.id">
					<img :src="snapshot.url" class="img-fluid" style="height: 320px" />
					<div class="bg-secondary text-white px-3 py-2">
						<span class="label">Kamera</span> : {{ snapshot.camera.name }}
						<br />
						<span class="label">Waktu</span> : {{ snapshot.time }}
					</div>
				</div>
			</div>
		</el-dialog>
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
      selectedData: {
        // id: 1,
        // time: '12-Jan-2021 19:00:12',
        // member: {
        //   name: 'Bagas',
        //   card_number: '123',
        //   plate_number: 'sss'
        // },
        // access_gate: {
        //   name: 'GATE 1',
        //   type: 'IN'
        // },
        // snapshots: [
        //   {url: 'https://via.placeholder.com/1280x720.png?text=SNAPSHOT', id: 1, camera: {name: 'KAMERA A'}, time: '12-Jan-2021 19:00:12'},
        //   {url: 'https://via.placeholder.com/1280x720.png?text=SNAPSHOT', id: 2, camera: {name: 'KAMERA B'}, time: '12-Jan-2021 19:00:12'},
        // ]
      },
      snapshotDialog: false
    }
  },

  methods: {
    showSnapshot(data) {
      this.snapshotDialog = true;
      this.selectedData = data;
    },

    deleteLog() {
      this.$confirm('Anda yakin akan menghapus semua log?', 'Konfirmasi', { type: 'warning' }).then(() => {
        this.$axios.$delete('/api/accessLogs').then(r => {
          this.$message({
            message: r.message,
            type: 'success',
            showClose: true
          });
          this.fetchData();
        }).catch(e => {
          this.$message({
            message: e.response.data.message,
            type: 'error',
            showClose: true
          })
        });
      }).catch(e => console.log(e));
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

<style lang="css" scoped>
span.label {
	display: inline-block;
	width: 100px;
	font-weight: bold;
}
</style>
