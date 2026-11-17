<?php

namespace Tests\Unit;

use App\Services\NomorIndukGenerator;
use Carbon\Carbon;
use InvalidArgumentException;
use Tests\TestCase;

class NomorIndukGeneratorTest extends TestCase
{
    private NomorIndukGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new NomorIndukGenerator;
    }

    public function test_extract_kode_asrama_digits(): void
    {
        $this->assertSame('001', $this->generator->extractKodeAsramaDigits('ASR-001'));
        $this->assertSame('002', $this->generator->extractKodeAsramaDigits('ASR-2'));
        $this->assertSame('015', $this->generator->extractKodeAsramaDigits('ASR-015'));
        $this->assertSame('120', $this->generator->extractKodeAsramaDigits('ASR-120'));
        $this->assertSame('007', $this->generator->extractKodeAsramaDigits('7'));
    }

    public function test_extract_kode_asrama_digits_throws_exception_on_invalid_input(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->generator->extractKodeAsramaDigits('ASR-NONE');
    }

    public function test_extract_tahun_masuk(): void
    {
        [$tahun, $yy] = $this->generator->extractTahunMasuk('2026-11-17');
        $this->assertSame(2026, $tahun);
        $this->assertSame('26', $yy);

        [$tahun27, $yy27] = $this->generator->extractTahunMasuk(Carbon::create(2027, 1, 15));
        $this->assertSame(2027, $tahun27);
        $this->assertSame('27', $yy27);
    }

    public function test_extract_tahun_masuk_throws_exception_on_blank(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->generator->extractTahunMasuk(null);
    }

    public function test_format_nomor_induk_standard_two_digits(): void
    {
        $this->assertSame('2600101', $this->generator->formatNomorInduk('26', '001', 1));
        $this->assertSame('2600102', $this->generator->formatNomorInduk('26', '001', 2));
        $this->assertSame('2600109', $this->generator->formatNomorInduk('26', '001', 9));
        $this->assertSame('2600110', $this->generator->formatNomorInduk('26', '001', 10));
        $this->assertSame('2600199', $this->generator->formatNomorInduk('26', '001', 99));
    }

    public function test_format_nomor_induk_widens_cleanly_when_exceeding_99(): void
    {
        $this->assertSame('26001100', $this->generator->formatNomorInduk('26', '001', 100));
        $this->assertSame('26001150', $this->generator->formatNomorInduk('26', '001', 150));
        $this->assertSame('260011000', $this->generator->formatNomorInduk('26', '001', 1000));
    }
}
