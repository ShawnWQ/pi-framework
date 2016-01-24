<?hh

namespace SpotEvents\ServiceModel;

class ReceivePaymentRequest {

	protected $chave;

	protected $entidade;

	protected $referencia;

	protected $valor;

	protected $datahorapag;

	protected $terminal;

	

    /**
     * Gets the value of chave.
     *
     * @return mixed
     */
    public function getChave()
    {
        return $this->chave;
    }

    /**
     * Sets the value of chave.
     *
     * @param mixed $chave the chave
     *
     * @return self
     */
    public function setChave($chave)
    {
        $this->chave = $chave;

        return $this;
    }

    /**
     * Gets the value of entidade.
     *
     * @return mixed
     */
    public function getEntidade()
    {
        return $this->entidade;
    }

    /**
     * Sets the value of entidade.
     *
     * @param mixed $entidade the entidade
     *
     * @return self
     */
    public function setEntidade($entidade)
    {
        $this->entidade = $entidade;

        return $this;
    }

    /**
     * Gets the value of referencia.
     *
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Sets the value of referencia.
     *
     * @param mixed $referencia the referencia
     *
     * @return self
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Gets the value of valor.
     *
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Sets the value of valor.
     *
     * @param mixed $valor the valor
     *
     * @return self
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Gets the value of datahorapag.
     *
     * @return mixed
     */
    public function getDatahorapag()
    {
        return $this->datahorapag;
    }

    /**
     * Sets the value of datahorapag.
     *
     * @param mixed $datahorapag the datahorapag
     *
     * @return self
     */
    public function setDatahorapag($datahorapag)
    {
        $this->datahorapag = $datahorapag;

        return $this;
    }

    /**
     * Gets the value of terminal.
     *
     * @return mixed
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * Sets the value of terminal.
     *
     * @param mixed $terminal the terminal
     *
     * @return self
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }
}