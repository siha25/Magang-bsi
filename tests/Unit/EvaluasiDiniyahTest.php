<?php

namespace Tests\Unit;

use App\Models\EvaluasiDiniyah;
use App\Models\User;
use App\Policies\EvaluasiDiniyahPolicy;
use Tests\TestCase;

class EvaluasiDiniyahTest extends TestCase
{
    public function test_model_uses_canonical_columns_and_date_casts(): void
    {
        $evaluasi = new EvaluasiDiniyah;

        $this->assertSame('evaluasi_diniyah', $evaluasi->getTable());
        $this->assertSame([
            'asrama_id',
            'anak_asuh_id',
            'jenis_evaluasi',
            'materi',
            'tanggal_evaluasi',
            'nilai',
            'predikat',
            'catatan_pengaju',
            'status',
            'catatan_revisi',
            'diajukan_oleh',
            'diajukan_pada',
            'dinilai_oleh',
            'dinilai_pada',
            'bulan',
            'tahun',
            'semester',
        ], $evaluasi->getFillable());
        $this->assertSame('date', $evaluasi->getCasts()['tanggal_evaluasi']);
        $this->assertSame('datetime', $evaluasi->getCasts()['diajukan_pada']);
        $this->assertSame('datetime', $evaluasi->getCasts()['dinilai_pada']);
    }

    public function test_model_relations_use_canonical_foreign_keys(): void
    {
        $evaluasi = new EvaluasiDiniyah;

        $this->assertSame('asrama_id', $evaluasi->asrama()->getForeignKeyName());
        $this->assertSame('anak_asuh_id', $evaluasi->anakAsuh()->getForeignKeyName());
        $this->assertSame('diajukan_oleh', $evaluasi->pengaju()->getForeignKeyName());
        $this->assertSame('dinilai_oleh', $evaluasi->penilai()->getForeignKeyName());
    }

    public function test_model_status_helpers_identify_workflow_states(): void
    {
        $this->assertSame(true, (new EvaluasiDiniyah(['status' => EvaluasiDiniyah::STATUS_PENDING]))->isPending());
        $this->assertSame(true, (new EvaluasiDiniyah(['status' => EvaluasiDiniyah::STATUS_DISETUJUI]))->isDisetujui());
        $this->assertSame(true, (new EvaluasiDiniyah(['status' => EvaluasiDiniyah::STATUS_PERLU_REVISI]))->isPerluRevisi());
    }

    public function test_approved_scope_selects_only_approved_status(): void
    {
        $query = (new EvaluasiDiniyah)->newQuery()->disetujui();

        $this->assertSame(['disetujui'], $query->getBindings());
    }

    public function test_only_approved_evaluations_are_marked_approved(): void
    {
        $pending = new EvaluasiDiniyah(['status' => EvaluasiDiniyah::STATUS_PENDING]);
        $approved = new EvaluasiDiniyah(['status' => EvaluasiDiniyah::STATUS_DISETUJUI]);
        $revisionRequested = new EvaluasiDiniyah(['status' => EvaluasiDiniyah::STATUS_PERLU_REVISI]);

        $this->assertSame(false, $pending->isDisetujui());
        $this->assertSame(true, $approved->isDisetujui());
        $this->assertSame(false, $revisionRequested->isDisetujui());
    }

    public function test_admin_cabang_can_submit_and_only_edit_its_revision_requests(): void
    {
        $policy = new EvaluasiDiniyahPolicy;
        $adminCabang = new User([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => 12,
        ]);
        $revisionRequest = new EvaluasiDiniyah([
            'asrama_id' => 12,
            'status' => EvaluasiDiniyah::STATUS_PERLU_REVISI,
        ]);
        $pendingEvaluation = new EvaluasiDiniyah([
            'asrama_id' => 12,
            'status' => EvaluasiDiniyah::STATUS_PENDING,
        ]);
        $otherAsramaRevision = new EvaluasiDiniyah([
            'asrama_id' => 13,
            'status' => EvaluasiDiniyah::STATUS_PERLU_REVISI,
        ]);

        $this->assertSame(true, $policy->create($adminCabang));
        $this->assertSame(true, $policy->update($adminCabang, $revisionRequest));
        $this->assertSame(false, $policy->update($adminCabang, $pendingEvaluation));
        $this->assertSame(false, $policy->update($adminCabang, $otherAsramaRevision));

        $adminCabangWithoutAsrama = new User([
            'role' => User::ROLE_ADMIN_CABANG,
            'asrama_id' => null,
        ]);
        $this->assertSame(false, $policy->create($adminCabangWithoutAsrama));
    }

    public function test_admin_pusat_can_review_while_pimpinan_pusat_is_read_only(): void
    {
        $policy = new EvaluasiDiniyahPolicy;
        $adminPusat = new User(['role' => User::ROLE_ADMIN_PUSAT]);
        $pimpinanPusat = new User(['role' => User::ROLE_PIMPINAN_PUSAT]);
        $evaluation = new EvaluasiDiniyah([
            'asrama_id' => 12,
            'status' => EvaluasiDiniyah::STATUS_PENDING,
        ]);

        $this->assertSame(true, $policy->update($adminPusat, $evaluation));
        $this->assertSame(true, $policy->view($pimpinanPusat, $evaluation));
        $this->assertSame(false, $policy->create($pimpinanPusat));
        $this->assertSame(false, $policy->update($pimpinanPusat, $evaluation));
        $this->assertSame(false, $policy->delete($adminPusat, $evaluation));
    }
}
